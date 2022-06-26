<?php

/*

    To consolidate the echoing of things into one location.

*/

function printScrapeButton() {

    $out = '<form method="post"> <input type="submit" name="bull"> Scrape a Random Covid Bulletin</></form>';
    echo $out;
    echo '<br>';

}

function printAllTable($DB) {

    echo '<h3>All previous values:</h3>';
    echo '<table class="covidResults" border="0" cellspacing="2" cellpadding="2"> 
    <tr> 
        <td> <font face="Arial">ID</font> </td> 
        <td> <font face="Arial">Bulletin Date</font> </td> 
        <td> <font face="Arial">Bulletin Number</font> </td> 
        <td> <font face="Arial">Scraped Date</font> </td> 
        <td> <font face="Arial">Prov Test Rate</font> </td> 
        <td> <font face="Arial">WPG Test Rate</font> </td> 
        <td> <font face="Arial">Case Number</font> </td> 
    </tr>';
    # GET ALL RECORDED VALUES
    $out = $DB->getAll();
    foreach ($out as &$value) {
        echo $value;
    }
    echo '</table>';
}

function printInitialResult($covid_today) {

    echo '<h3>The following was just scraped:</h3>';
    echo '<table class="covidResults" border="0" cellspacing="2" cellpadding="2"> 
    <tr>
        <td> <font face="Arial">ID</font> </td> 
        <td> <font face="Arial">Bulletin Date</font> </td> 
        <td> <font face="Arial">Bulletin Number</font> </td> 
        <td> <font face="Arial">Scraped Date</font> </td> 
        <td> <font face="Arial">Prov Test Rate</font> </td> 
        <td> <font face="Arial">WPG Test Rate</font> </td> 
        <td> <font face="Arial">Case Number</font> </td> 
    </tr>';

    echo $covid_today;

    echo '</table><br>' ;


}

function printHeader() {
    # READ AND ECHO HEADER
    $header = fopen("header.html", "r") or die("unable to open file D");
    echo fread($header, filesize("header.html"));
    fclose($header);

    $README = fopen("README.html", "r") or die("unable to open file D");
    echo fread($README, filesize("README.html"));
    fclose($README);
}

function printFooter() {
    # READ AND ECHO FOOTER
    $footer = fopen("footer.html", "r") or die("unable to open file you absolute human");
    echo fread($footer, filesize("footer.html"));
    echo "Finished doing things for now, thanks" . "<br>";
}


?>