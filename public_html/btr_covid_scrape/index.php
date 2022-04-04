<?php

require_once("covidDataObj.php");
require_once("mysql_impl.php");
require_once("scrape_data.php");

$db_info = "mysql_info.txt";

# READ AND ECHO HEADER
$header = fopen("header.html", "r") or die("unable to open file D");
echo fread($header, filesize("header.html"));
fclose($header);

$README = fopen("README.html", "r") or die("unable to open file D");
echo fread($README, filesize("README.html"));
fclose($README);

# CREATE DB
$DB = new mysql_table($db_info);



// NEW UPDATE, RECORD DATA IN DB
if ($update_date !== -1) {
    
    $covid_today = get_data($doc, $update_date, FALSE);
    $DB->insert($covid_today);
    echo "<p> NEW DATA PRINTED BELOW";
    echo "<br>" . $covid_today;
}
// UPDATE last_update.txt to reflect todays check
else {
    echo "<br>" . "<p> UP TO DATE";
    $curr_day = intval(date('d', time()));

    $update_file = fopen("last_update.txt", "r") or die("Unable to open last_update");
    $last_update = intval(fgetc($update_file));
    fclose($update_file);

    $update_file = fopen("last_update.txt", "w") or die("Unable to open last_update");
    fwrite($update_file, $last_update);
    fwrite($update_file, $curr_day);
    fclose($update_file);

}





echo '<table border="0" cellspacing="2" cellpadding="2"> 
      <tr> 
          <td> <font face="Arial">ID</font> </td> 
          <td> <font face="Arial">bulletin_date</font> </td> 
          <td> <font face="Arial">scraped_date</font> </td> 
          <td> <font face="Arial">prov_test_rate</font> </td> 
          <td> <font face="Arial">wpg_test_rate</font> </td> 
          <td> <font face="Arial">todays_cases</font> </td> 
      </tr>';
# GET ALL RECORDED VALUES
$out = $DB->getAll();
foreach ($out as &$value) {
    echo $value;
}

// $DB->delete(694396800);

// LOOP OVER TABLE, PRINT ALL VALUES


# READ AND ECHO FOOTER
$footer = fopen("footer.html", "r") or die("unable to open file you absolute human");
echo fread($footer, filesize("footer.html"));
echo "Finished doing things for now, thanks" . "<br>";

?>
