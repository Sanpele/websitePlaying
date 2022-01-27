<?php

define('KB', 1024);
define('MB', 1048576);

class PersonObj {

    private static $num_person = 0;
    private static $allowed_mb = 10;

    private $id;
    public $username;
    private $pic_directory;
    private $privacy;

    private $space_quota;
    private $password;
    private $pass_hash;
    private $ipaddress;

    protected function __construct() {

    }

    public static function newPerson($row) {
        $instance = new self();

        $num = intval(file_get_contents("objects/num_people.txt"));
        $row['id'] = $num;
        $num++;

        $num_w = fopen("objects/num_people.txt", "w") or die("unable to open file");
        fwrite($num_w, $num);
        fclose($num_w);

        $instance->fill($row);

        return $instance;
    }

    public static function withRow($row) {
        $instance = new self();
        $instance->fill($row);
        return $instance;
    }

    protected function fill(array $row) {
        $this->id = $row['id'] ?? -1;
        $this->username = $row['username'] ?? NULL;
        $this->pic_directory = $row['pic_directory'] ?? NULL;
        $this->privacy = $row['privacy'] ?? 1;
        $this->space_quota = $row['space_quota'] ?? NULL; 
        $this->password = $row['password'] ?? NULL;
        $this->pass_hash = $row['pass_hash'] ?? NULL;
        $this->ipadress = $row['ipaddress'] ?? NULL;
    }

    public function getIP() {
        return $this->ipadress;
    }

    public function getPass() {
        return $this->password;
    }

    public function checkPass($pass) {
        if ($pass === $this->password)
            return TRUE;
        else 
            return FALSE;
    }

    public function getUser() {
        return $this->username;
    }

    public function getPrivacy() {
        return $this->privacy;
    }

    public function getPassHash() {
        return $this->pass_hash;
    }

    public function addQuota($additional_space) {
        // only update if have room. 
        if ($additional_space + $this->space_quota < self::$allowed_mb * MB) {
            $this->space_quota += $additional_space;
            return TRUE;
        }
        else 
            return FALSE;
    }
    
    public function getQuota() {
        return $this->space_quota;
    }

    public function getPicDir() {
        return $this->pic_directory;
    }

    public function getID() {
        return $this->id;
    }

    public function __toString() {
        $format = " <br>
        User : %s<br>
        Your privacy is set to : %s. This means you have no privacy and your images are being displayed publicly.<br>
        Space used  = %d kb / %d kb<br>";
        $out = sprintf($format, $this->username, $this->privacy ? 'True' : 'False', $this->space_quota / 1000, (self::$allowed_mb * MB) / 1000);
        return $out;
    }

}

?>