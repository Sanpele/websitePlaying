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
        
        if ($this->conn->query($sql) === TRUE) {
            echo "Table Created or maybe already there<br>";
        }
        else {
            echo "TABLE CREATION ERROR Error:	" . $sql . "<br>" . $this->conn->error . "<br>";
        }
    }


    public function insert ($CovidDataObj) {

        $check_already = $this->conn->query("SELECT * from covid_scrape2 WHERE id = '$date_id'");

        if (mysqli_num_rows($check_already) > 0) {
            echo "<p class = 'p'> Data already recorded today<br>";
        }
        else {
            $sql = "INSERT INTO covid_scrape2 (id, today_date, prov_rate, wpg_rate, daily_num)
                VALUES ('$CovidDataObj->id', '$CovidDataObj->date', '$CovidDataObj->prov_test_rate', '$CovidDataObj->wpg_test_rate', '$CovidDataObj->todays_cases')";

            if ($this->conn->query($sql) === TRUE) {
                echo "<p class = 'p'> New Record Created Succesfully<br>";
            }
            else {
                echo "<p class = 'p'> INSERTION ERROR Error:	" . $sql . "<br>" . $this->conn->error . "<br>";
            }
        }

    }

    public function get ($id) {

    }

    public function delete ($id) {

    }

    public function getAll () {



        $sql = "SELECT * FROM covid_scrape2";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){ 	
                echo "<br><br>";
                echo "id				: " . $row["id"] . "<br>";
                echo "date				: " . $row["today_date"] . "<br>";
                echo "provincial rate	: " . $row["prov_rate"] . "<br>";
                echo "wpg rate			: " . $row["wpg_rate"] . "<br>";
                echo "todays cases		: " . $row["daily_num"] . "<br>";
            }
        }
    }

    public function __toString() {

    }
}

?>