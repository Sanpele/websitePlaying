<?php

require_once("db_template.php");

class mysql_table implements db_template {

    private $servername;
    private $username;
    private $password;
    private $dbname;
    
    private $conn;

    
    public function __construct($ser, $usr, $pas, $dbn) {
        $this->servername = $ser;
        $this->username = $usr;
        $this->password = $pas;
        $this->dbname = $dbn;

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        $sql = "CREATE TABLE IF NOT EXISTS covid_scrape2 ( 
            id INT(9) NOT NULL PRIMARY KEY,
            today_date DATE,
            prov_rate DECIMAL(2,1) NOT NULL, 
            wpg_rate DECIMAL(2,1) NOT NULL,
            daily_num int(4) NOT NULL
        )";
        
        if ($conn->query($sql) === TRUE) {
            echo "Table Created or maybe already there<br>";
        }
        else {
            echo "TABLE CREATION ERROR Error:	" . $sql . "<br>" . $conn->error . "<br>";
        }

    }

    public function insert ($CovidDataObj) {

    }

    public function get ($id) {

    }

    public function delete ($id) {

    }

    public function getAll () {

    }

    public function __toString() {

    }
}

?>