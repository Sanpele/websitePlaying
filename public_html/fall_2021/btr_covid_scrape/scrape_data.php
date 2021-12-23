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

	$proper_len = strlen($sample);
	$pos = strpos($entire, $after);	
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
	return rtrim($str);

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

function parse_document($bug) {

	$response = get_document();

	$dom = new DomDocument();
	@$dom-> loadHTML($response);
	$xpath = new DOMXpath($dom);
	
	$current = $xpath->query("//*[@id='coronavirus-current']"); // list  
	
	$whole = $current->item(1)->nodeValue."<br>";
	$whole = str_replace("\xc2\xa0", ' ', $whole);
	
	$check_pos = strpos($whole, "rate is ");
	#echo $check_pos."<br>";
	$check_str = substr($whole, $check_pos+strlen("rate is "));
	#echo "check_str = $check_str"."<br>";
	
	$current_date = date("Y-m-d");

	// echo "NEW CURRENT DATE" . $current_date . "<-----";

	// Check date Matches todays date, consider generating date with php to have nice format


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

	$date_id = gen_date_id();

	return $covid_today = new CovidData($date_id, $current_date, $current_prov_pos, $current_wpg_pos, $cases_second);

}

?>