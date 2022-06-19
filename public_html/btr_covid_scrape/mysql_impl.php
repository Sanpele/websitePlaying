<?php

require_once("db_template.php");
require_once("covidDataObj.php");

class mysql_table implements db_template {

    private $servername;
    private $username;
    private $password;
    private $dbname;
    
    private $conn;

    private $tableName = "covidAttemptTestNew";


    public function __construct() {

        $file = fopen("mysql_info.txt","r");
        $this->servername = trim(fgets($file));
        $this->username = trim(fgets($file));
        $this->password = trim(fgets($file));
        $this->dbname = trim(fgets($file));
        fclose($file);

        // echo '<p> servername : ' . $this->servername . '</p>';

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        if ($this->conn === FALSE) {
            echo "<p>Initial connection error :	" . $sql . "<br>" . $this->conn->error . "<br>";
            return;
        }

        $val = $this->conn->query('select 1 from $this->tableName LIMIT 1');
        if($val === FALSE)
        {
            $this->createDB();
        }
    }
    
    public function createDB() {

        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName (
            id INT(9) NOT NULL PRIMARY KEY,
            prov_rate DECIMAL(3,1) NOT NULL, 
            wpg_rate DECIMAL(3,1) NOT NULL,
            daily_num int(4) NOT NULL,
            bulletin_date DATE,
            scraped_date DATE
        )";


        
        if ($this->conn->query($sql) === FALSE) {

            echo '<p> servername : ' . $sql . '</p>';
            echo "<p>TABLE CREATION ERROR :	" . $sql . "<br>" . $this->conn->error . "<br>";
        }
    }


    public function insert ($CovidDataObj) {

        $check_already = $this->conn->query("SELECT * from $this->tableName WHERE id = $CovidDataObj->id");

        if (mysqli_num_rows($check_already) > 0) {
            // echo "<p class = 'p'> Data already recorded today <br>";
            $this->delete($CovidDataObj->id);
        }
        
        $sql = "INSERT INTO $this->tableName (id, prov_rate, wpg_rate, daily_num, bulletin_date, scraped_date)
            VALUES ('$CovidDataObj->id', '$CovidDataObj->prov_test_rate', '$CovidDataObj->wpg_test_rate', '$CovidDataObj->todays_cases', '$CovidDataObj->bulletin_date', '$CovidDataObj->scraped_date')";

        if ($this->conn->query($sql) === FALSE) {
            echo "<p> INSERTION ERROR Error:	" . $sql . "<br>" . $this->conn->error . "<br>";
        }
    }


    public function get ($id) {
        $sql = "SELECT FROM $this->tableName where id=$id";
        $row = $this->conn->query($sql);
        # CHECK RESULT SOMEHOW

        $out;
        if ($row === TRUE) {
            $out = new CovidData($row['id'], $row['bulletin_date'], $row['scraped_date'], $row['prov_rate'], $row['wpg_rate'], $row['daily_num']);
        }
        else {
            echo "Error retriving record: " . $row->error;
        }

        return $out;
    }


    public function delete ($id) {

        $sql = "DELETE FROM $this->tableName where id=$id";
        $result = $this->conn->query($sql);

        if ($result === FALSE) {
            echo "<br>" . "Error deleting record: " . $result->error;
        }
    }


    /*
        should prob return a list of covidDataObj, will need to unpack rows
    */
    public function getAll () {

        $sql = "SELECT * FROM $this->tableName";
        $result = $this->conn->query($sql);

        $out = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc())
                $out[] = new CovidData($row['id'], $row['bulletin_date'], $row['scraped_date'], $row['prov_rate'], $row['wpg_rate'], $row['daily_num']);           
        }
        return $out;
    }

}

?>