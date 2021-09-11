<?php

$header = fopen("header.html", "r") or die("unable to open file D;");

echo fread($header, filesize("header.html"));

//echo '<p>hello world, im gonna getcha</p>';

$footer = fopen("footer.html", "r") or die("unable to open file you human");

echo fread($footer, filesize("footer.html"));

?>
