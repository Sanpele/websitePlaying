<?php

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

$_SESSION['logged_in'] = 1; // log in on reload
$_SESSION['public'] = 1; // display all public images
$_SESSION['username'] = "guest"; // guest user

header("Location: ../index.php");


?>