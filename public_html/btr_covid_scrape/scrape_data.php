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

	// echo $response;

    return $response;
}


function clean($string) {
	$string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
 
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}

function get_date($str, $bug) {

	if ($bug) {
		echo '<p>' . $str;
	}

	$str = clean($str);

	if ($bug) {
		echo '<p>' . $str;
	}


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

	$sample = "COVID-19-BULLETIN";

	$proper_start = strlen($sample);
	$start_cutoff = strpos($str, $sample);

	$max_length = strlen("February 16, 2022");
	$cutoff = 0;
    $num_space = 0;

	$char = $str[$start_cutoff - $cutoff - 1];
	while($num_space < 3) {
		if ($bug) {
			echo "<p> $char, next =" . $str[$start_cutoff - $cutoff-2];
		}

        // we have hit a -
        if (ord($char) === 45) {

            $num_space += 1;

			while ($num_space < 3 and ord($str[$start_cutoff - $cutoff-2]) === 45) {
				$cutoff++;
				$char = $str[$start_cutoff - $cutoff-1];
			}

        }

		$cutoff++;
		$char = $str[$start_cutoff - $cutoff-1];
	}


	$date_str = substr($str,($start_cutoff - $cutoff + 8), $cutoff - 8);

	$new_str = '';
	$last_char = '';

	// remove repeated - 
	for ($i = 0; $i < strlen($date_str); $i++) {
		if (ord($date_str[$i]) !== 45 or ord($last_char) !== 45) {
			$new_str .= $date_str[$i];
		}
		$last_char = $date_str[$i];
	}


	$plode = explode("-", $new_str);

	if ($bug) {
		echo '<p> start_cutoff = ' . $start_cutoff;
		echo '<p> cutoff = ' . $cutoff;
		echo '<p> date_str->>' . $date_str . '<---';	
		print_r($plode);
	}



	$month = $month_to_num[$plode[0]];
	$month_num = $month < 10 ? "0".$month : $month;

	if ($bug) {
		echo "<p> plode[0] : " . $plode[0] . "<-";
		echo "<p> fromArr  : " . $month_to_num["February"] . "<-";
		echo "<p> eq       : " . "February" === $plode[0];
		echo "<p> len       : " . strlen("February");
		echo "<p> len       : "  .strlen($plode[0]);
		echo "<p> month_num : " . $month_num;
	}

    // day is 1 char or 2
    $day = $plode[1][1] === ',' ? substr($plode[1], 0, 1) : substr($plode[1], 0, 2);

	$final_date = $plode[2] . "-" . $month_num . "-" . $day;

	// echo "<p> $final_date";

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

function parse_document($my_url) {

	// echo "<p class='p'> PARSING IS STARTING";

	$response = get_document($my_url);

	$dom = new DomDocument();
	@$dom-> loadHTML($response);

	$xpath = new DOMXpath($dom);
	
	$current = $xpath->query('//div[contains(@class,"col-inside-3")]'); // list  

	$whole = 'abc';

	if ($current->length === 0) {
		echo '<p> Empty Node list';
	}
	else {
		$whole = $current->item(0)->nodeValue."<br>";
		$whole = str_replace("\xc2\xa0", ' ', $whole);
	}

	// echo $whole;

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

function get_cases($whole, $bug) {

	$first_go = intval(get_val_after($whole, "cases today to ", "481", $bug));

	if ($first_go !== 0) 
		return $first_go;

	$second_go = intval(get_val_after($whole, "new cases of ", "481", $bug));

	if ($second_go !== 0)
		return $second_go;

}

/*
	Parse provided document and return a covidDataObj with data from document.
	
*/
function get_data($whole, $url, $bug) {

	$current_date = date("Y-m-d");

	$current_prov_pos = get_val_after($whole, "rate is ", "10.5", $bug);
	$current_wpg_pos = get_val_after($whole, "provincially and ", "12.6", $bug);
	$bull_date = get_date($whole, $bug);

	// Function to try couple case numbers
	$current_cases = get_cases($whole, $bug);
	
	// before ' new cases of the '

	$bulletin_number = get_val_after($whole, "COVID-19 BULLETIN #", "333", $bug);
	$bulletin_url = $url;
	

	if ($bug == TRUE) {
		echo "WHOLE = " . $whole;

		echo "Date = $current_date"."<br>";
		echo "prov test rate = $current_prov_pos"."<br>";
		echo "wpg test rate = $current_wpg_pos"."<br>";
		// echo "corrected # cases = $current_cases"."<br>";
		echo "second attempt cases = $current_cases" . "<br>";
		echo "bulletin_number = $bulletin_number" . "<br>";
		echo "bulletin_url = $bulletin_url" . "<br>";

	}

	// current day as int
	$curr_day = intval(date('d', time()));

	$covid_today = new CovidData( $bull_date, $bulletin_number, $bulletin_url, $current_date, $current_prov_pos, $current_wpg_pos, $current_cases);

	if ($bug === TRUE) {
		echo $covid_today;
	}

	return $covid_today;
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