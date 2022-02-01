<?php

$header = fopen("header.html", "r") or die("unable to open file D;");

echo fread($header, filesize("header.html"));

$README = fopen("README.html", "r") or die("unable to open file D;");

echo fread($README, filesize("README.html"));

// topics
$topics = array(

	"Covid Scrape with MYSQL" => "btr_covid_scrape/index.php",
	"St Charles Picture Display" => "charles_pictures/index.php",
	"Christmas Presents" => "christmas/index.php?person=all",
    "Summer 2020" => "summer_2020/index.html",

);

// loop over values in enum
function makeButtons($buttons) {

	$html = '';
	foreach ($buttons as $k => $v) {		 
		$html .= '<a class="button1" href="' . $v. '">' . $k . ' </a></div>';
	}	
	$html .= "</div>";
	echo $html;

}

makeButtons($topics);

$footer = fopen("footer.html", "r") or die("unable to open file you human");

echo fread($footer, filesize("footer.html"));

?>
