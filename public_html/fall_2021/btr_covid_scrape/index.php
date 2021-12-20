<?php

require_once("covidDataObj.php");
require_once("mysql_impl.php");
require_once("scrape_data.php");

echo "<p class = 'p'> Hello There";

$header = fopen("header.html", "r") or die("unable to open file D");
echo fread($header, filesize("header.html"));
fclose($header);


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

$footer = fopen("footer.html", "r") or die("unable to open file you absolute human");
echo fread($footer, filesize("footer.html"));

echo "Finished doing things for now, thanks" . "<br>";

?>
