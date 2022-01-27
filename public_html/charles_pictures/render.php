<?php

ini_set('session.save_path',realpath(dirname($_SERVER['DOCUMENT_ROOT']) . '/session'));
session_start();

function printHeader() {
    # READ AND ECHO HEADER
    $header = fopen("hypertext/header.html", "r") or die("unable to open file");
    echo fread($header, filesize("hypertext/header.html"));
    fclose($header);
}

function printFooter() {
    # READ AND ECHO FOOTER
    $footer = fopen("hypertext/footer.html", "r") or die("unable to open file");
    echo fread($footer, filesize("hypertext/footer.html"));
    fclose($footer);
}

function printLogout() {
    echo '
    <br>
    <br>
    <form action="redirects/log_out_redirect.php" method="post">
    <Button type="submit" name="Log Out" />Log Out</Button>
    </form>
    <hr>

    ';
}

function printSignUp() {
    echo '
    <form action="redirects/log_in_guest_redirect.php" method="post">
    Sign in as Guest : <Button type="submit" name="Sign In" />Guest Sign In</Button>
    </form>
    <form action="redirects/log_out_redirect.php" method="post">
    Existing User : <Button type="submit" name="Sign In" />Existing User (Sign In)</Button>
    </form>
    <form action="sign_up.php" method="post">
    <p>Sign Up (with a new username and password) :</p>
    <p>Your username: <input type="text" name="username" /></p>
    <p>Your password: <input type="text" name="password" /></p>
    <p><input type="submit" name="Sign In" /></p>
    </form>

    <hr>

    ';
}

function printSignIn() {
    echo '
    <form action="redirects/sign_up_redirect.php" method="post">
    Sign Up : <Button type="submit" name="Sign Up" />New User (Sign Up)</Button>
    </form>
    ';

    // if they have guessed wrong previously
    if (isset($_SESSION['user_bad_pass']) AND $_SESSION['user_bad_pass'] === 1) {
        // minimally i really should only allow users a few tries. 
        // Ideally, get some capatchas involved
        echo "This user exists but you have provided the wrong password, try again"; 
        unset($_SESSION['user_bad_pass']);
    }

    echo '
    <form action="handle_login_form.php" method="post">
    <p>Sign In</p>
    <p>Your username: <input type="text" name="username" /></p>
    <p>Your password: <input type="text" name="password" /></p>
    <p><input type="submit" name="Sign In" /></p>
    </form>

    <hr>
    ';
}


/*
    prints some of the imformation about the user
*/
function printInfo($person) {
    
    if (!isset($person)) { // just guest info
        echo "<br>You are a Guest, please take off your shoes before entering the house";

        echo "<br>To upload images, you must make an account <br> Thanks ";
    }   
    else { // user logged in, display full info
        echo "<br>Specific, relevant and in-depth user info";
        echo "<br>" . $person;

        // abbility for a user to toggle between their images and all images
        echo '
        <form action="redirects/public_switch_redirect.php" method="post">
        <Button type="submit" name="public_switch" />Switch Public/Private</Button>
        </form>
        ';

        // abbility for user to upload images
        echo "<p> Images must be one of the following formats: jpeg, jpg, png, or gif </p>";
        echo "<p> Likewise the max filesize of an image is 2mb currently, with total space allowed being 20mb of images";
        echo '
        <form action="upload.php" method="post" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
        </form>
        ';
    }   

    echo "<hr>";

}

function printRefreshButton() {
    echo '
    <form action="redirects/refresh.php" method="post">
    <Button type="submit" name="Sign In" />Take Me Back</Button>
    </form>
    ';
}

/*
    lil option for user to view only their pictures or all images.
*/
function printFilter() {

}

/*
    creates html for images in directories passes from $arr_of_dir
*/
function printImages($arr_of_dir) {
    // echo "Pictures are of the following Dir";
    // print_r($arr_of_dir);
    $num_cols = 4;
    $num_pics = 0;
    $pic_names = array();

    $pics = "pics/";

    if (count($arr_of_dir) == 0) {
        echo "<br> No Images to display";
        return;
    }

    // for each directory
    foreach ($arr_of_dir as $sub_dir) {
        $dir = new DirectoryIterator($pics . $sub_dir);
        // for each file in directory
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) { // if not a dotfile
                $num_pics+=1;
                $pic_names[] = $pics . $sub_dir . $fileinfo->getFilename(); // add name to array
            }
        }
    }
    
    $html = "";
    
    $remainder = $num_pics;
    for ($i = 0; $i < intval($num_pics / $num_cols) + 1; $i++) { // calcuate and loop over the number of rows to be displayed
        $html .= '<div class="row">';
        for ($j = 0; $j < $num_cols && $remainder > 0; $j++) { // loop over all picturs in row, stoping early if no more imgs.
            $html .= '
            <div class="column">
            <img class="picture_list_1" src="' . $pic_names[$i * $num_cols + $j] . '" alt = "Picture Sequence" style="width: 100%">
            </div>';
            $remainder--;
        }
        $html .= "</div>";
    }

    echo $html;

}

?>