<?php

assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

require_once("testing.php");
require_once("render.php");
require_once("admin.php");

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

$testing = FALSE; // if true, run display results of testing.php 

function load_picture_page() {
    printHeader();

    // display logout button
    printLogout();

    // display user info
    printUserInfo();

    // display all user pics
    displayPics();

    printFooter();
}

function load_menu_page() {
    printHeader();

    // check if user is still logged in from previous visit
    if (checkCookie()) {
        // if yes, display pictures
        load_picture_page();
    }
    // Otherwise, display sign-in imformation
    else {
        printSignIn();
    }

    printFooter();
}

function load_sign_up() {
    printHeader();

    // display sign-up page
    printSignUp();

    printFooter();

}

// if $testing boolean is true, run test file instead of image repo code.
if ($testing) { 
    runAllTests();
}
else {

    // check if we are logging out, if yes unset cookie
    if (isset($_SESSION['clr_cookie']) AND $_SESSION['clr_cookie'] === 1) {

        unset($_COOKIE['uname']);
        unset($_SESSION['cookie']);

        $_SESSION['clr_cookie'] = 0;
    }


    // sign-up
    if ((!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] === 0) AND isset($_SESSION['sign_up']) AND $_SESSION['sign_up'] === 1) {
        load_sign_up();
    }
    // sign-in
    else if (!isset($_SESSION['logged_in']) or $_SESSION['logged_in'] === 0)
        load_menu_page();
    // display pictures
    else 
        load_picture_page();
}
?>