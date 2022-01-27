<?php

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

if ($_SESSION['public'] === 1)
    $_SESSION['public'] = 0;
else
    $_SESSION['public'] = 1;

header("Location: ../index.php");

?>