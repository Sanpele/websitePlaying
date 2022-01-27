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


# Only scrape if we haven't already checked today
if (!check_scrape()) {

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
    // UPDATE last_update.txt to reflect todays check
    else {
        echo "<br>" . "<p class='p'> UP TO DATE";
        $curr_day = intval(date('d', time()));

        $update_file = fopen("last_update.txt", "r") or die("Unable to open last_update");
        $last_update = intval(fgetc($update_file));
        fclose($update_file);

        $update_file = fopen("last_update.txt", "w") or die("Unable to open last_update");
        fwrite($update_file, $last_update);
        fwrite($update_file, $curr_day);
        fclose($update_file);

    }

}
else {

    echo "<br>" . "<p class='p'> ALREADY CHECKED TODAY, JUST LIST DB";

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
