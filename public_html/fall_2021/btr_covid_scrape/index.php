<?php

require_once("covidDataObj.php");
require_once("mysql_impl.php");
require_once("scrape_data.php");

$db_info = "mysql_info.txt";

echo "<p class = 'p'> Hello There";

$header = fopen("header.html", "r") or die("unable to open file D");
echo fread($header, filesize("header.html"));
fclose($header);

$covid_today = parse_document(FALSE);

// echo "<p class = 'p'> RUNNING SCRAPER MODULE BELOW.<br>";

// echo $covid_today;

$DB = new mysql_table($db_info);

$DB->insert($covid_today);

$DB->getAll();

$DB->delete(694310400);

echo "<p class='p'> PRINTING AGAIN";

$DB->getAll();

// LOOP OVER TABLE, PRINT ALL VALUES



$footer = fopen("footer.html", "r") or die("unable to open file you absolute human");
echo fread($footer, filesize("footer.html"));

echo "Finished doing things for now, thanks" . "<br>";

?>
