<?php

// topics
$topics = array(

	"Covid Scrape with MYSQL" => "btr_covid_scrape/index.php",
	"St Charles Picture Display" => "charles_pictures/index.php"

);

// loop over values in enum
function makeButtons($buttons) {

	$html = '<div class="list">';
	foreach ($buttons as $k => $v) {		 
		$html .= '<div class="listItem"> <a class="button" href="' . $v. '">' . $k. ' </a></div>';
	}	
	$html .= "</div>";
	echo $html;

}


$header = fopen("header.html", "r") or die("unable to open file D;");
echo fread($header, filesize("header.html"));

echo '<p class="p">some somewhat intersting, (temporarily?) terible looking things</p>';

makeButtons($topics);

$footer = fopen("footer.html", "r") or die("unable to open file you human");
echo fread($footer, filesize("footer.html"));

?>
