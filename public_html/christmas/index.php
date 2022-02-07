<?php

// topics
$people = array(

	"general" => array(0, "this shouldent be diisplayed"),
	"L" => array(1, "Liam "),
	"M_D" => array(2, "Mom & Dad"),
	"G_G" => array(3, "Grandma & Grampa"),
	"A_Z" => array(4, "Anne & Zev"),
	"P_C" => array(5, "Patric & Cristine"),
	"testing" => array(0, "this shouldent be diisplayed"),
	"all" => array(0, "All"),
);

$pictures = array (
	array("sunrise_0.jpg","sunrise_2.jpg","sunrise_3.jpg"),
	array("nother_0.jpg","nother_2.jpg","nother_3.jpg"),
	array("yeller_0.jpg","yeller_2.jpg","yeller_3.jpg"),
	array("mirror_0.jpg","mirror_2.jpg","mirror_3.jpg"),
	array("strife_0.jpg","strife_2.jpg","strife_3.jpg"),
	array("bubble_0.jpg","bubble_2.jpg","bubble_3.jpg"),
	array("tree_0.jpg","tree_2.jpg","tree_3.jpg"),
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

echo '<p class="p">Merry Christmas + Happy Holidays ' . $people[$person][1] . '! </p>';

echo '<p class="p">Personal, unique and touching msg</p>';

echo '<p class="actual_text">
	The images I used were some of my photos I took over the summer at St. Charles. </br> </br>

	The images in the first column are 3000 x 4000 pixel images I took with my sweet phone camera. </br> </br>

	The second column are the images produced from a nifty Processing program that I modified a bit. It chooses a random pixel in the image and draws an elipse
	on that spot in another image. After repeating enough times you recreate the original image. The original program and a better descrption can be found that 
	can be found near the bottom of https://processing.org/tutorials/pixels </br> </br>

	Finally, to get column 3 I ordered ordered a 8 x 8 inch canvas print from staples. </br> </br>

	So really, this gift is a canvas print of a divined image of a picture, and you are viewing a Image of an Image of the canvas... </br> </br>

</p>'; 

if ($person != "all") { // just display one set of images

	$index = $people[$person][0];
	$addon = "pictures/";

	$html = '<div class="row">';

	$html .= '<div class="column">';
	$html .= '<img class="picture_list_1" src="' . $addon.$pictures[$index][0] . '" alt = "Picture Sequence" style="width: 100%">';
	$html .= '</div>';

	$html .= '<div class="column">';
	$html .= '<img class="picture_list_2" src="' . $addon.$pictures[$index][1] . '" alt = "Picture Sequence" style="width: 100%">';
	$html .= '</div>';

	$html .= '<div class="column">';
	$html .= '<img class="picture_list_3" src="' . $addon.$pictures[$index][2]. '" alt = "Picture Sequence" style="width: 100%">';
	$html .= '</div>';


	$html .= "</div>";
	echo $html;

}

else { // diplay all imgs

	$addon = "pictures/";
	foreach($pictures as $arr) { // loop over every set

		$html = '<div class="row">';
		// loop over corresponding 4 pictures

		$html .= '<div class="column">';
		$html .= '<img class="picture_list_1" src="' . $addon.$arr[0] . '" alt = "Picture Sequence" style="width: 100%">';
		$html .= '</div>';
	
		$html .= '<div class="column">';
		$html .= '<img class="picture_list_2" src="' . $addon.$arr[1] . '" alt = "Picture Sequence" style="width: 100%">';
		$html .= '</div>';
	
		$html .= '<div class="column">';
		$html .= '<img class="picture_list_3" src="' . $addon.$arr[2]. '" alt = "Picture Sequence" style="width: 100%">';
		$html .= '</div>';

		$html .= "</div>";

		echo $html;
	}

}


$footer = fopen("footer.html", "r") or die("unable to open file you human");
echo fread($footer, filesize("footer.html"));

?>
