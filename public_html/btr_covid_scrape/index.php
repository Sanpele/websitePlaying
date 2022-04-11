<?php

require_once("mysql_impl.php");
require_once("scrape_manager.php");
require_once("printer.php");

$db_info = "mysql_info.txt";
$DB = new mysql_table($db_info);

function scrapeThings() {
    
    $db_info = "mysql_info.txt";
    $DB = new mysql_table($db_info);

    $covid_today = scrape_random();
    
    $DB->insert($covid_today);
    echo "<p> NEW DATA PRINTED BELOW";
    echo "<br>" . $covid_today;
}

printHeader();

printScrapeButton();

if (isset($_POST['bull'])) {
    scrapeThings();
    echo '<p> Hello World, Im gonna getcha' . 'br';
}

printAllTable($DB);

printFooter();

?>
