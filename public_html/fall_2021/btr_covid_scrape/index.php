<?php

require_once("covidDataObj.php");
require_once("mysql_impl.php");
require_once("scrape_data.php");

$db_info = "mysql_info.txt";

echo "<p class = 'p'> Hello There";

# READ AND ECHO HEADER
$header = fopen("header.html", "r") or die("unable to open file D");
echo fread($header, filesize("header.html"));
fclose($header);

# CREATE DB
$DB = new mysql_table($db_info);

# CALL SCRAPER AND GET DOCUMENT
$doc = parse_document();

# CHECK IF NEW UPDATE
$update_date = check_new_update($doc, FALSE);

// NEW UPDATE, RECORD DATA IN DB
if ($update_date !== -1) {
    $covid_today = get_data($doc, $update_date, FALSE);
    $DB->insert($covid_today);
    echo "<br>" . "<p class='p'> NEW DATA PRINTED BELOW";
    echo "<br>" . $covid_today;
}
else {
    echo "<br>" . "<p class='p'> UP TO DATE";
}

# GET ALL RECORDED VALUES
$DB->getAll();

// $DB->delete(694396800);

// LOOP OVER TABLE, PRINT ALL VALUES


# READ AND ECHO FOOTER
$footer = fopen("footer.html", "r") or die("unable to open file you absolute human");
echo fread($footer, filesize("footer.html"));
echo "Finished doing things for now, thanks" . "<br>";

?>
