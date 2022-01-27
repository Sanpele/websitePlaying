<?php

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

$_SESSION['logged_in'] = 0; // log in on reload
$_SESSION['sign_up'] = 1;

header("Location: ../index.php");


?>