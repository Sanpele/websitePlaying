<?php

require_once("covidDataObj.php");

interface db_template {

    public function __construct($init_file);
    public function insert ($CovidDataObj);
    public function get ($id);
    public function delete ($id);
    public function getAll ();

}

?>