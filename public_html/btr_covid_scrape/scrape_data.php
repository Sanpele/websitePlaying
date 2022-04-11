<?php

require_once("covidDataObj.php");

$debug = FALSE;

function get_document($my_url) {
    $file = fopen("info.txt","r");
    $my_key = trim(fgets($file));
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

function get_date($str) {

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

	$sample = "Last updated: ";

	$proper_start = strlen($sample);
	$start_cutoff = strpos($str, $sample) + $proper_start;
	$max_length = strlen("February 16, 2022");
	$cutoff = 0;

	$char = $str[$start_cutoff + $cutoff];
	while((ord($char) >= '0' or ord($char) === 44 or ord($char) === 32) and $cutoff < $max_length) {
		// echo "<p> $char";
		$cutoff++;
		$char = $str[$start_cutoff + $cutoff];
	}

	$date_str = substr($str,$start_cutoff, $cutoff);

	$plode = explode(" ", $date_str);
	$month = $month_to_num[$plode[0]];
	$month_num = $month < 10 ? "0".$month : $month;
	
	echo "<p> plode[0] : " . $plode[0] . "<-";
	echo "<p> fromArr  : " . $month_to_num["February"] . "<-";
	echo "<p> eq       : " . "February" === $plode[0];
	echo "<p> len       : " . strlen("February");
	echo "<p> len       : "  .strlen($plode[0]);
	echo "<p> month_key : " . $month_key;
	echo "<p> month_num : " . $month_num;

	$final_date = $plode[2] . "-" . $month_num . "-" . substr($plode[1], 0, 2);

	echo "<p> $final_date";

	return $final_date;

}

function get_val_after($entire, $prev, $sample, $bug) {

    $proper_len = strlen($sample);
    $date_pos = strpos($entire, $prev);
    $cutoff = $proper_len;

    if ($bug == TRUE) {
        echo "proper_len ".$proper_len."<br>";
        echo "date_pos ".$date_pos."<br>";
    }
 
    while($entire[$date_pos+strlen($prev)+$cutoff] > '0' and $cutoff >= 0) {
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
	$doc_date = get_val_after($whole, "Last updated: February ", "52", $bug);
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
	$bull_date = get_date($whole);
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

	return $covid_today = new CovidData($date_id, $bull_date, $current_date, $current_prov_pos, $current_wpg_pos, $cases_second);

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