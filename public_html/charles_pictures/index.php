<?php


$header = fopen("header.html", "r") or die("unable to open file D;");

echo fread($header, filesize("header.html"));

echo '<p class = "p"> hehe yeah im just about to start on this, have some things to figure out :D</p>'; 

$footer = fopen("footer.html", "r") or die("unable to open file you human");

echo fread($header, filesize("footer.html"));

?>