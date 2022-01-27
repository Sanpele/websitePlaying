<?php

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

$_SESSION['username'] = "";
$_SESSION['logged_in'] = 0;
$_SESSION['sign_up'] = 0;
$_SESSION['clr_cookie'] = 1;

header("Location: ../index.php");

?>