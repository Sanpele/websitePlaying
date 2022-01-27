<?php

require_once("render.php");

require_once("DB/db_manager.php");
require_once("DB/SQlite3_DB.php");

require_once("objects/PersonObj.php");

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

$target_dir = "pics/" . $_SESSION['username'] . '/';
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
  if($check !== false) {
    echo "<br>File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "<br>File is not an image, Or it may be too big";
    $uploadOk = 0;
  }
}

if ($_SESSION['username'] === "guest" AND $uploadOk) {
  echo "<br>Sorry, Guests are not allowed to upload files. Create and account if you would like to upload";
  $uploadOk = 0;
}

// Check if file already exists
if (file_exists($target_file) AND $uploadOk) {
  echo "<br>Sorry, file already exists.";
  $uploadOk = 0;
}

// Check file size
if ($_FILES["fileToUpload"]["size"] > 2 * MB AND $uploadOk) {
  echo "<br>Sorry, your file is too large.";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"&& $imageFileType != "gif" AND $uploadOk) {
  echo "<br>Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
  $uploadOk = 0;
}

// check if space in users quota
if ($uploadOk === 1) {
  $username = $_SESSION['username'];
  $db_ctl = new db_manager();
  $db = $db_ctl->getDB();

  $person = $db->getByName($username);
  if (isset($person)) {

    if (!$person->addQuota($_FILES["fileToUpload"]["size"])) {
      echo "<br> Sorry, there was an error updating your quota, you may not have enough room left to upload any more files";
      $uploadOk = 0;
    }
    else {
      // update quota in db
      if ($db->updateQuota($person))
        echo "<br>Successful update of quota :D";
      else {
        echo "<br>Quota Update Failed D:";
        $uploadOk = 0;
      }
    }
  }
}


// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "<br>Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} 
else {
  if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    echo "<br>The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";



  } else {
    echo "<br>Sorry, there was an error uploading your file.";
  }
}

printRefreshButton();


?>