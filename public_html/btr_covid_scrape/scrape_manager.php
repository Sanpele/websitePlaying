<?php

require_once("scrape_data.php");

/*

    To handle the logic of calling the scraping functions declared in scrape_data.php

*/


function scrape($url) {

}

function scrape_random() {

    $file_path = "complet_url.txt";
    $lines = count(file($file_path));

    $url_num = rand(300, 400);

    $url = "";

    $f = fopen($file_path, 'r');
    if ($f) {
        for ($i = 0; $i < $url_num; $i++) {
            $line = fgets($f);
        }    
        $url = $line;
    }
    fclose($f);

    $pieces = explode(" ", $url);

    $doc = get_document($pieces[0]);

    $covid_today = get_data($doc, $update_date, FALSE);

    return $covid_today;
}



?>