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

//print_r($response);

# commenting, okok nice nice. 
#
# now i need to parse the html to hopefully extract data i want


$dom = new DomDocument();
@$dom-> loadHTML($response);
$xpath = new DOMXpath($dom);

$current = $xpath->query("//*[@id='coronavirus-current']"); // list  
#$child_stuff = $current[1]->getElementsByTagName('p'); //DOMNodeList 
#

// print_r($current)."<br><br>";

$whole = $current->item(1)->nodeValue."<br>";
$whole = str_replace("\xc2\xa0", ' ', $whole);

//print_r($whole);

// parse whole to find
// Date
// Test Positivity provincial
// Test Positivity winnipeg
// Total corrected # cases


// $entire is the haystack
// prev is the needle
// sample is a sample of the intended data to be found

function get_val_after($entire, $prev, $sample) {
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

function get_val_before($entire, $after, $sample) {

	$proper_len = strlen($sample);
	$pos = strpos($entire, $after);	
	
//	echo "Length = " . $proper_len . "<br>";
//	echo "strpos = " . $pos . "<br>";

	$offset = $pos - $proper_len;

//	echo "offset= " . $offset. "<br>";

	$str = substr($entire, $offset, $proper_len);

//	echo "Str = " . $str . "<br>";

	if (is_null($str)) {
		echo "Something went wrong with get_val_before, fix eeeet"."<br>";
	}

	return rtrim($str);

}



$check_pos = strpos($whole, "rate is ");
#echo $check_pos."<br>";
$check_str = substr($whole, $check_pos+strlen("rate is "));
#echo "check_str = $check_str"."<br>";

$current_date = get_val_after($whole, "Last updated: ", "September 81, 2021");
$current_prov_pos = get_val_after($whole, "rate is ", "10.5");
$current_wpg_pos = get_val_after($whole, "provincially and ", "12.6");
// $current_cases = get_val_after($whole, "cases today", "485");

$cases_second = get_val_before($whole, "cases today", "123");


//echo "Date = $current_date"."<br>";
//echo "prov test rate = $current_prov_pos"."<br>";
//echo "wpg test rate = $current_wpg_pos"."<br>";
//echo "corrected # cases = $current_cases"."<br>";
//echo "second attempt cases = $cases_second" . "<br>";


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

//echo "curr unix : ".time()."<br>";
//echo "curr date : ".date('y-m-d', time())."<br>";

// seconds since 2000
$date_id = 31536000 * date('y', time());

$days_since = 0;
for ($i = 1; $i < date('m', time()); $i++) {
	$days_since += $days_in_month[$i];	
}
$days_since += date('d', time());
$date_id += 86400 * $days_since;

//echo "timestamp = " . time() . "<br>";
//echo "unique date id = $date_id <br>";

//echo "Finished, thanks <br>";

$servername = "colinwaugh.com";
$username = "u47y5qzsjrvxm";
$password = "2@22i2c@h2>e";
$dbname = "dbvheujc1qg3qq";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
     die("Connection failed: " . $conn->connect_error);
} 

$sql = "CREATE TABLE IF NOT EXISTS covid_scrape ( 
	id INT(9) NOT NULL PRIMARY KEY,
	prov_rate DECIMAL(2,1) NOT NULL, 
	wpg_rate DECIMAL(2,1) NOT NULL,
	daily_num int(4) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
	echo "Table Created or maybe already there<br>";
}
else {
	echo "TABLE CREATION ERROR Error:	" . $sql . "<br>" . $conn->error . "<br>";
}

$check_already = $conn->query("SELECT * from covid_scrape WHERE id = '$date_id'");

if (mysqli_num_rows($check_already) > 0) {
	echo "User ALreayd Exists <br>";
}
else {
	$sql = "INSERT INTO covid_scrape (id, prov_rate, wpg_rate, daily_num)
		VALUES ('$date_id', '$current_prov_pos', '$current_wpg_pos', '$cases_second')";

	if ($conn->query($sql) === TRUE) {
		echo "New Record Created Succesfully<br>";
	}
	else {
		echo "INSERTION ERROR Error:	" . $sql . "<br>" . $conn->error . "<br>";
	}
}

// LOOP OVER TABLE, PRINT ALL VALUES

$sql = "SELECT * FROM covid_scrape";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()){ 	
		echo "<br><br>";
		echo "id				: " . $row["id"] . "<br>";
		echo "provincial rate	: " . $row["prov_rate"] . "<br>";
		echo "wpg rate			: " . $row["wpg_rate"] . "<br>";
		echo "todays cases		: " . $row["daily_num"] . "<br>";
	}
	
}

echo "Finished doing things for now, thanks" . "<br>";

?>
