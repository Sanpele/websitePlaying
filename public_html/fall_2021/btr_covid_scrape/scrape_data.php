<?php

require_once("covidDataObj.php");

$debug = FALSE;

$days_in_month = array ( 
	1 => 31,
	2 => 28, 
	3 => 31,
	4 => 30,
	5 => 31, 
	6 => 30, 
	7 => 31,
	8 => 31,
	9 => 30,
	10 => 31,
	11 => 30,
	12 => 31
);

$month_to_num = array(
	"January" => 1,
	"February" => 2, 
	"March" => 3,
	"April" => 4, 
	"May" => 5,
	"June" => 6, 
	"July" => 7,
	"August" => 8,
	"September" => 9,
	"October" => 10, 
	"November" => 11,
	"December" => 12, 
);

function get_document() {
    $file = fopen("info.txt","r");
    $my_key = trim(fgets($file));
    $my_url = trim(fgets($file));
    fclose($file);
    
    $url = "http://api.scraperapi.com?api_key=$my_key&url=$my_url";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}

function get_val_after($entire, $prev, $sample, $bug) {

    $proper_len = strlen($sample);
    $date_pos = strpos($entire, $prev);
    $cutoff = $proper_len;

    if ($bug == TRUE) {
        echo "proper_len ".$proper_len."<br>";
        echo "date_pos ".$date_pos."<br>";
    }
 
    while(!is_numeric($entire[$date_pos+strlen($prev)+$cutoff]) and $cutoff >= 0) {
        --$cutoff;
        if ($bug == TRUE) {
            echo "cutoff ".$cutoff."<br>";
        }
    }

    $date_str = substr($entire, $date_pos+strlen($prev), $cutoff+1);
	if ($bug == TRUE) {
    	echo "len = $proper_len, pos = $date_pos, prev len = ".strlen($prev)."<br>";
	}

    return $date_str;
}

function get_val_before($entire, $after, $sample, $bug) {

	$proper_len = 2;
	$pos = strpos($entire, $after);	

	while ($entire[$pos - $proper_len] !== " ") {
		// echo "Looping " . $entire[$pos - $proper_len];
		$proper_len += 1;
	}

	$proper_len -= 2;

	if ($bug == TRUE) {
		echo "Length = " . $proper_len . "<br>";
		echo "strpos = " . $pos . "<br>";
	}

	$offset = $pos - $proper_len - 1;
	if ($bug == TRUE) {
		echo "offset= " . $offset. "<br>";
	}

	$str = substr($entire, $offset, $proper_len);

	if (is_null($str)) {
		echo "Something went wrong with get_val_before, fix eeeet"."<br>";
	}
	if ($bug == TRUE) {
		echo "Str = " . $str . "<br>";
	}

	$str = rtrim ($str); # get rid of any whitespace

	$goodString = str_replace(',','', $str); # remove comma if present in string

	return $goodString;

}

function gen_date_id() {
	$date_id = 31536000 * date('y', time());

	$days_since = 0;
	for ($i = 1; $i < date('m', time()); $i++) {
		$days_since += $days_in_month[$i];	
	}
	$days_since += date('d', time());
	$date_id += 86400 * $days_since;

	return $date_id;
}

function parse_document() {

	// echo "<p class='p'> PARSING IS STARTING";

	$response = get_document();

	$dom = new DomDocument();
	@$dom-> loadHTML($response);
	$xpath = new DOMXpath($dom);
	
	$current = $xpath->query("//*[@id='coronavirus-current']"); // list  
	
	$whole = $current->item(1)->nodeValue."<br>";
	$whole = str_replace("\xc2\xa0", ' ', $whole);

	return $whole;

}
	

/*
	Checks if the date in $whole is different from our last recorded date. 

	
	return : int, -1 if update is old. if update new return date of update
*/
function check_new_update($whole, $bug) {

	// Grab the day number in current month 
	$doc_date = get_val_after($whole, "Last updated: January ", "52", $bug);
	$doc_date = intval($doc_date);

	// Read the latest update from file
	$update_file = fopen("last_update.txt", "r") or die("Unable to open last_update");
	$last_update = intval(fgetc($update_file));
	fclose($update_file);

	// whether we updated today
	if ($doc_date === $last_update) {
		return -1;
	}
	else {
		return $doc_date;
	}
}

/*
	Parse provided document and return a covidDataObj with data from document.
	
*/
function get_data($whole, $update_date, $bug) {

	$current_date = date("Y-m-d");

	$current_prov_pos = get_val_after($whole, "rate is ", "10.5", $bug);
	$current_wpg_pos = get_val_after($whole, "provincially and ", "12.6", $bug);
	// $current_cases = get_val_after($whole, "cases today", "485");
	
	$cases_second = get_val_before($whole, "cases today", "123", $bug);

	if ($bug == TRUE) {
		echo "Date = $current_date"."<br>";
		echo "prov test rate = $current_prov_pos"."<br>";
		echo "wpg test rate = $current_wpg_pos"."<br>";
		// echo "corrected # cases = $current_cases"."<br>";
		echo "second attempt cases = $cases_second" . "<br>";
	}

	// unique value going to be DB id
	$date_id = gen_date_id();

	// current day as int
	$curr_day = intval(date('d', time()));

	$update_file = fopen("last_update.txt", "w") or die("Unable to open last_update");
	fwrite($update_file, $update_date);
	fwrite($update_file, $curr_day);
	fclose($update_file);

	return $covid_today = new CovidData($date_id, $current_date, $current_prov_pos, $current_wpg_pos, $cases_second);

}

/*
	checks file to see if we have updated file today
*/
function check_scrape() {

	$curr_day = intval(date('d', time()));

	$update_file = fopen("last_update.txt", "r") or die("Unable to open last_update");
	fgetc($update_file); 
	$date_last_check = intval(fgetc($update_file));
	fclose($update_file);

	return $curr_day === $date_last_check;

}

?>