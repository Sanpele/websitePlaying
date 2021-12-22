<?php

// topics
$people = array(

	"general" => 0,
	"Liam" => 1,
	"Mom_Dad" => 2,
	"Grandma_Grampa" => 3,
	"Anne_Zev" => 4,
	"Patrick_Cristine" => 5,
	"testing" => 0,
	"all" => 0,

);

$pictures = array (
	array("sunrise_0.jpg","sunrise_2.jpg","sunrise_2.jpg"),
	array("nother_0.jpg","nother_2.jpg","nother_2.jpg"),
	array("yeller_0.jpg","yeller_2.jpg","yeller_2.jpg"),
	array("mirror_0.jpg","mirror_2.jpg","mirror_2.jpg"),
	array("strife_0.jpg","strife_2.jpg","strife_2.jpg"),
);

$header = fopen("header.html", "r") or die("unable to open file D;");
echo fread($header, filesize("header.html"));


$person;

if (isset($_GET['person'])) {
	$person = $_GET['person'];
}
else {
	$person = "general";
}

echo '<p class="p">Merry Christmas ' . $person . '! </p>';

echo '<p class="p">Personal, unique and touching msg</p>';

echo '<p class="actual_text">
	The images I used were some of my photos I took over the summer at St. Charles. </br> </br>

	The images in the first column are the 3000 x 4000 pixel images i took with my sweet phone camera. </br> </br>

	The second column are the images produced from a nifty Processing program that can be found near the bottom of https://processing.org/tutorials/pixels </br> </br>

	Finally, to get the column 3 result I took the column 2 images and ordered a 8 x 8 inch canvas print from staples then took pictures of them. </br> </br>
</p>'; 

if ($person != "all") { // just display one set of images

	$index = $people[$person];
	$addon = "pictures/";

	$html = '<div class="row">';
	// loop over corresponding 4 pictures
	foreach($pictures[$index] as $value) {
		$html .= '<div class="column">';
		$html .= '<img class="picture_list" src="' . $addon.$value . '" alt = "Picture Sequence" style="width: 100%">';
		$html .= '</div>';
	}
	$html .= "</div>";
	echo $html;

}

else { // diplay all imgs

	$addon = "pictures/";
	foreach($pictures as $arr) { // loop over every set

		$html = '<div class="row">';
		// loop over corresponding 4 pictures
		foreach($arr as $value) {
			$html .= '<div class="column">';
			$html .= '<img class="picture_list" src="' . $addon.$value . '" alt = "Picture Sequence" style="width: 100%">';
			$html .= '</div>';
		}
		$html .= "</div>";
		echo $html;
	}

}


$footer = fopen("footer.html", "r") or die("unable to open file you human");
echo fread($footer, filesize("footer.html"));

?>
