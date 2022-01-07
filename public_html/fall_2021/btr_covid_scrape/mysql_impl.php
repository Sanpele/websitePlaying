<?php

require_once("db_template.php");

class mysql_table implements db_template {

    private $servername;
    private $username;
    private $password;
    private $dbname;
    
    private $conn;

    private $tableName = "covidAttempt";

    
    public function __construct($init) {

        $file = fopen($init,"r");
        $this->servername = trim(fgets($file));
        $this->username = trim(fgets($file));
        $this->password = trim(fgets($file));
        $this->dbname = trim(fgets($file));
        fclose($file);

        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);

        $sql = "CREATE TABLE IF NOT EXISTS $this->tableName (
            id INT(9) NOT NULL PRIMARY KEY,
            prov_rate DECIMAL(2,1) NOT NULL, 
            wpg_rate DECIMAL(2,1) NOT NULL,
            daily_num int(4) NOT NULL,
            today_date DATE
        )";
        
        if ($this->conn->query($sql) === TRUE) {
            // echo "Table Created or maybe already there<br>";
        }
        else {
            echo "TABLE CREATION ERROR Error:	" . $sql . "<br>" . $this->conn->error . "<br>";
        }
    }


    public function insert ($CovidDataObj) {

        $check_already = $this->conn->query("SELECT * from $this->tableName WHERE id = $CovidDataObj->id");

        if (mysqli_num_rows($check_already) > 0) {
            // echo "<p class = 'p'> Data already recorded today <br>";
        }
        else {
            $sql = "INSERT INTO $this->tableName (id, prov_rate, wpg_rate, daily_num, today_date)
                VALUES ('$CovidDataObj->id', '$CovidDataObj->prov_test_rate', '$CovidDataObj->wpg_test_rate', '$CovidDataObj->todays_cases', '$CovidDataObj->date')";

            if ($this->conn->query($sql) === TRUE) {
                echo "<p class = 'p'> New Record Created Succesfully<br>";
            }
            else {
                echo "<p class = 'p'> INSERTION ERROR Error:	" . $sql . "<br>" . $this->conn->error . "<br>";
            }
        }
    }

    public function get ($id) {
        $sql = "SELECT FROM $this->tableName where id=$id";
        $result = $this->conn->query($sql);
        # CHECK RESULT SOMEHOW

        if ($result === TRUE) {
            echo "Record RETRIEVED Succssfully";
        }
        else {
            echo "Error retriving record: " . $result->error;
        }

        return $result;
    }

    public function delete ($id) {

        $sql = "DELETE FROM $this->tableName where id=$id";
        $result = $this->conn->query($sql);
        # CHECK RESULT SOMEHOW

        if ($result === TRUE) {
            echo "Record Deleted Succssfully";
        }
        else {
            echo "Error deleting record: " . $result->error;
        }

    }


    /*
        should prob return a list of covidDataObj, will need to unpack rows
    */
    public function getAll () {
        $sql = "SELECT * FROM $this->tableName";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()){ 	
                echo "<br><br>";
                echo "id				: " . $row["id"] . "<br>";
                echo "provincial rate	: " . $row["prov_rate"] . "<br>";
                echo "wpg rate			: " . $row["wpg_rate"] . "<br>";
                echo "todays cases		: " . $row["daily_num"] . "<br>";
                echo "date				: " . $row["today_date"] . "<br>";
                echo "<br>";
            }
        }
    }

}

?>