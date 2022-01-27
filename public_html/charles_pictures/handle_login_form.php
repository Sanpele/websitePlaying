<?php

require_once("admin.php");

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

if (isset($_POST['username']) AND isset($_POST['password']) AND $_POST['username'] !== "" AND $_POST['password'] !== "") {
    $user = htmlspecialchars($_POST['username']);
    $pass = htmlspecialchars($_POST['password']);

    $_SESSION['username'] = $user;

    trySignIn($user, $pass);

}
else {
    $_SESSION['error_msg'] = "Your username / password was not allowed, please try again";
    header("Location: index.php");
}

?>