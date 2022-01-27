<?php

require_once("admin.php");

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

if (isset($_POST['username']) AND isset($_POST['password']) AND $_POST['username'] !== "" AND $_POST['password'] !== "") {

    $user = htmlspecialchars($_POST['username']);
    $pass = htmlspecialchars($_POST['password']);

    signUp($user, $pass);

    $_SESSION['username'] = $user; // set username 
    $_SESSION['logged_in'] = 1; // log in on reload
    $_SESSION['public'] = 1; // display public images
    header("Location: index.php");

    $_SESSION['sign_up'] = 0;
}
else {
    $_SESSION['error_msg'] = "Your username/password was not allowed, please try again";
    header("Location: index.php");
}



?>