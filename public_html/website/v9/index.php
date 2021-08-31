<?php

echo "<p> Hello There </p>";

$file = fopen("info.txt","r");

$my_key = trim(fgets($file));
$my_url = trim(fgets($file));

#echo $my_key."<br>";
#echo $my_url."<br>";

fclose($file);

$url = "http://api.scraperapi.com?api_key=$my_key&url=$my_url";

#echo $url."<br>";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
$response = curl_exec($ch);
curl_close($ch);

# print_r($response);

# commenting, okok nice nice. 
#
# now i need to parse the html to hopefully extract data i want


$dom = new DomDocument();
@$dom-> loadHTML($response);
$xpath = new DOMXpath($dom);

$current = $xpath->query("//*[@id='coronavirus-current']"); // list  
#$child_stuff = $current[1]->getElementsByTagName('p'); //DOMNodeList 
#

#echo print_r($current)."<br><br>";

$whole = $current->item(1)->nodeValue."<br>";
$whole = str_replace("\xc2\xa0", ' ', $whole);
#echo print_r($whole);

// parse whole to find
// Date
// Test Positivity provincial
// Test Positivity winnipeg
// Total corrected # cases


// $entire is the haystack
// prev is the needle
// sample is a sample of the intended data to be found

function get_val($entire, $prev, $sample) {
    $proper_len = strlen($sample);
    $date_pos = strpos($entire, $prev);
    $cutoff = $proper_len;
 
    #echo "proper_len ".$proper_len."<br>";
    #echo "date_pos ".$date_pos."<br>";
 
 
    while(!is_numeric($entire[$date_pos+strlen($prev)+$cutoff]) and $cutoff >= 0) {
        --$cutoff;
       # echo "cutoff ".$cutoff."<br>";
 
    }
 
    $date_str = substr($entire, $date_pos+strlen($prev), $cutoff+1);
 
    #echo "len = $proper_len, pos = $date_pos, prev len = ".strlen($prev)."<br>";
 
    return $date_str;
 }

$check_pos = strpos($whole, "rate is ");
#echo $check_pos."<br>";
$check_str = substr($whole, $check_pos+strlen("rate is "));
#echo "check_str = $check_str"."<br>";

$current_date = get_val($whole, "Last updated: ", "May 8, 2021");
$current_prov_pos = get_val($whole, "rate is ", "10.5");
$current_wpg_pos = get_val($whole, "provincially and ", "12.6");
$current_cases = get_val($whole, "number of cases today to ", "485");


echo "Date = $current_date"."<br>";
echo "prov test rate = $current_prov_pos"."<br>";
echo "wpg test rate = $current_wpg_pos"."<br>";
echo "corrected # cases = $current_cases"."<br>";


?>
