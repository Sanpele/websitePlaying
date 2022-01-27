<?php

require_once("db_interface.php");

class sqlite_imp implements db_interface {

    private static $db;

    public function __construct() {
        sqlite_imp::$db = new SQLite3(DB_NAME . '.sqlite', SQLITE3_OPEN_CREATE | SQLITE3_OPEN_READWRITE);

        sqlite_imp::$db->query('CREATE TABLE IF NOT EXISTS DB_NAME (
            "id" INTEGER PRIMARY KEY NOT NULL,
            "username" VARCHAR,
            "pic_directory" VARCHAR,
            "privacy" BIT,
            "space_quota" INTEGER,
            "password" VARCHAR,
            "pass_hash" VARCHAR,
            "ipaddress" VARCHAR
        )');

        // print_r(sqlite_imp::$db);

        return sqlite_imp::$db;
    }
    public function insert($person) {
        $statement = sqlite_imp::$db->prepare('INSERT INTO DB_NAME ("id", "username", "pic_directory", "privacy", "space_quota", "password", "pass_hash", "ipaddress") 
            VALUES (:id, :username, :pic_directory, :privacy, :space_quota, :pass, :pass_hash, :ipaddress)');
        $statement->bindValue(':id', $person->getID());
        $statement->bindValue(':username', $person->username);
        $statement->bindValue(':pic_directory', $person->getPicDir());

        if ($person->getPrivacy() == TRUE)
            $statement->bindValue(':privacy', 1);
        else
            $statement->bindValue(':privacy', 0);

        $statement->bindValue(':space_quota', $person->getQuota());
        $statement->bindValue(':pass', $person->getPass());
        $statement->bindValue(':pass_hash', $person->getPassHash());
        $statement->bindValue(':ipaddress', $person->getIP());

        $statement->execute(); // you can reuse the statement with different values
    }

    public function updateQuota($person) {
        $statement = sqlite_imp::$db->prepare('UPDATE DB_NAME SET "space_quota" = ? WHERE "id" = ?');
        $statement->bindValue(1, $person->getQuota());
        $statement->bindValue(2, $person->getID());
        $result = $statement->execute();
        return $result ? TRUE : FALSE;
    }
    
    /*
        Query DB and unpack results, returning array of PersonObj.
    */
    public function getAllPublic() {
        $statement = sqlite_imp::$db->prepare('SELECT * FROM DB_NAME WHERE "privacy" = 0');
        $result = $statement->execute();

        $arr = array();
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            if ($row != NULL)
                $arr[] = PersonObj::withRow($row);
        }
        $result->finalize();

        return $arr;
    }
    public function getByID($id) {
        $statement = sqlite_imp::$db->prepare('SELECT * FROM DB_NAME WHERE "id" = ?');
        $statement->bindValue(1, $id);
        $result = $statement->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        $person = NULL;
        if ($row != NULL) {
            $person = PersonObj::withRow($row);
        }

        // THROW CUSTOM ERROR IF RESULT IS NULL?

        return $person;

    }

    public function getByHash($hash) {
        $statement = sqlite_imp::$db->prepare('SELECT * FROM DB_NAME WHERE "pass_hash" = ?');
        $statement->bindValue(1, $hash);
        $result = $statement->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        $person = NULL;
        if ($row != NULL) {
            $person = PersonObj::withRow($row);
        }

        // THROW CUSTOM ERROR IF RESULT IS NULL?

        return $person;
    }

    public function getByName($username) {
        $statement = sqlite_imp::$db->prepare('SELECT * FROM DB_NAME WHERE "username" = ?');
        $statement->bindValue(1, $username);
        $result = $statement->execute();
        $row = $result->fetchArray(SQLITE3_ASSOC);

        $person = NULL;
        if ($row != NULL) {
            $person = PersonObj::withRow($row);
        }

        // THROW CUSTOM ERROR IF RESULT IS NULL?

        return $person;
    }
    public function delete($id) {
        $statement = sqlite_imp::$db->prepare('DELETE FROM DB_NAME WHERE "id" = ?');

        $statement->bindValue(1, $id);

        $result = $statement->execute();
        return $result ? TRUE : FALSE;
    }


    
    public function userCount() {
        $userCount = sqlite_imp::$db->querySingle('SELECT COUNT(DISTINCT "id") FROM DB_NAME');
        return $userCount;
    }

    public function deleteAll() {
        $query = "DELETE FROM DB_NAME"; // Query to delete all records 
        $sql = sqlite_imp::$db->prepare($query);

        if($sql->execute()){
            // echo "Successfully deleted  records ";
        }
        else{
            print_r($sql->errorInfo()); // if any error is there it will be posted
            $msg = " Database problem, please contact site admin ";
        }
    }


}

?>