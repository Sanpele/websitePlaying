<?php

define("DB_NAME", "db_proper");
// define("DB_NAME", "people");

interface db_interface {
    
    public function __construct();
    public function insert($person);
    public function updateQuota($person);
    public function getAllPublic();
    public function getByID($id);
    public function getByHash($hash);
    public function getByName($username);
    public function delete($id);
    
}

?>