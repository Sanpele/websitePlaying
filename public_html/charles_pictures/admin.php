<?php

require_once("DB/db_manager.php");
require_once("DB/SQlite3_DB.php");
require_once("render.php");
require_once("objects/PersonObj.php");

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

/*
    checks cookies to verify is a user is logged in
*/
function checkCookie() {

    print_r($_COOKIE);

    $uname = $_COOKIE['uname']; 
    if (!empty($uname)) {   

        $db_ctl = new db_manager();
        $db = $db_ctl->getDB();
        $person = $db->getByHash($uname);

        if ($person !== NULL) {
            $_SESSION['username'] = $person->getUser();
            $_SESSION['user_is_loggedin'] = 1;
            $_SESSION['cookie'] = $uname;
            return TRUE;
        }
        
    }
    return FALSE;
}

/*
    If we have a session user who is not guest, get them print their info
*/
function printUserInfo() {

    $db_ctl = new db_manager();
    $db = $db_ctl->getDB();

    $person = NULL;
    if (isset($_SESSION['username']) AND $_SESSION['username'] !== "guest") {
        $user = $_SESSION['username'];
        $person = $db->getByName($user);
    }
    if ($person)
        printInfo($person);
    else
        echo "<br> You are a Guest";
}


/*
    Adds the user to DB, creates / sets a cookie for the user.
*/
function signUp($user, $pass) {

    $db_ctl = new db_manager();
    $db = $db_ctl->getDB();

    // print_r($_POST);

    $user_ip = $_SERVER['REMOTE_ADDR'];
    $cookie_hash = md5(sha1($user. $user_ip));

    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; 
    setcookie("uname",$cookie_hash,time()+3600*24*365,'/', $domain, false);
    
    $person_arr = toArr($user, $user . '/', FALSE, $pass, $cookie_hash, $user_ip);
    mkdir("pics/" . $person_arr['pic_directory']);
    $new_person = PersonObj::newPerson($person_arr);
    $db->insert($new_person);

}


/*
    Checks DB for user-pass combination.
    if in DB, redirect to display info / pictures.
    if nay, redirect to sign user up.
*/
function trySignIn($user, $pass) {

    $db_ctl = new db_manager();
    $db = $db_ctl->getDB();

    $user = $_SESSION['username'];
    $person = $db->getByName($user);

    if ($person === NULL) { // not in DB, 
        // Redirect to Sign up
        $_SESSION['login_msg'] = "Im sorry, you don't seem to exist. Why not try signing up with a new acount?";
        $_SESSION['sign_up'] = 1;
        
        header("Location: index.php");
    }
    
    else { // Person Exists, log in
        
        if ($person->checkPass($pass)) {

            $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; 
            setcookie("uname",$cookie_hash,time()+3600*24*365,'/', $domain, false);

            $_SESSION['username'] = $user; // set username
            $_SESSION['logged_in'] = 1; // log user in on reload
            $_SESSION['public'] = 1; // display all public images on reload
            header("Location: index.php");
        
        }
        else {
            $_SESSION['user_bad_pass'] = 1;
            header("Location: index.php");
        }

    }

}

/*
    Display all pictures.

    Constructs an array of all picture directorys to display and passes 
    this to printImages build the html.
*/
function displayPics() {

    $db_ctl = new db_manager();
    $db = $db_ctl->getDB();
    $all_people = $db->getAllPublic();

    // If we are displaying all public images
    if (isset($_SESSION['public']) AND $_SESSION['public'] === 1) {
        echo "<br> PRINTING ALL IMAGES";
        $all_people = $db->getAllPublic();

        $array_of_dir = array();

        // loop over all public people in DB and add the dir to a
        foreach ($all_people as $person) {
            $array_of_dir[] = $person->getPicDir();
        }

        printImages($array_of_dir);
    }
    // Just displaying the users images
    else {
        echo "<br> PRINTING JUST YOUR IMAGES";

        $user = $_SESSION['username'];
        $person = $db->getByName($user);

        if ($person === NULL) { // error, person we operating on not in DB, this should never be reached
            echo "<br>BIG ERROR, WE THE PERSON FOUND IN THE SESSION VARIABLE IS NOT IN OUR DB, THAT DOSEN't MAKE MUCH SENSE";
        }
        else {
            printImages(array($person->getPicDir()));
        }

    }
}

/*
    Helper function to assist in PersonObj creation.
*/
function toArr($name, $dir, $privacy, $pass, $hash, $ip) {

    $arr = array();
    $arr['id'] = PHP_INT_MAX;
    $arr['username'] = $name;
    $arr['pic_directory'] = $dir;
    $arr['privacy'] = $privacy;
    $arr['space_quota'] = 0;
    $arr['password'] = $pass;
    $arr['pass_hash'] = $hash;
    $arr['ipaddress'] = $ip;

    return $arr;

}

?>