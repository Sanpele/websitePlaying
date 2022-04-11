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
    echo '<table border="0" cellspacing="2" cellpadding="2"> 
    <tr> 
        <td> <font face="Arial">bulletin date</font> </td> 
        <td> <font face="Arial">scraped date</font> </td> 
        <td> <font face="Arial">prov test rate</font> </td> 
        <td> <font face="Arial">wpg test rate</font> </td> 
        <td> <font face="Arial">case number</font> </td> 
    </tr>';
    # GET ALL RECORDED VALUES
    $out = $DB->getAll();
    foreach ($out as &$value) {
        echo $value;
    }
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