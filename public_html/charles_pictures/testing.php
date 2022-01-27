<?php

require_once("objects/PersonObj.php");

require_once("DB/db_manager.php");
require_once("DB/SQlite3_DB.php");

assert_options(ASSERT_ACTIVE, TRUE);
assert_options(ASSERT_BAIL,   TRUE);
assert_options(ASSERT_WARNING, 1);
assert_options(ASSERT_QUIET_EVAL, 1);



/*
    Testing File. Things to test : 

    DB implementation & admin logic effect on DB
*/

function runAllTests() {
    echo "<br> ------------- STARTING TESTING ------------- <br> <br>";
    assert(true);

    testInsert();
    testGetByID();
    testDeleteByID();
    testDeleteAll();
    test_addQuota();
    test_updateQuota();

    echo "<br> ------------- PASSED ALL TESTS --------------";
}

function test_template() {
    echo "<br> ------------ Testing test_template";

    $db_ctl = new db_manager();
    $db_ctl->resetDB();

    $db = $db_ctl->getDB();

    $a1 = toArr("Colin", "Colin/", TRUE, "Waugh", "*****", $_SERVER['REMOTE_ADDR']);
 
    $person1 = PersonObj::newPerson($a1);

    $db->insert($person1);


    echo "<br> ------------ PASSED ";

}

function test_updateQuota() {
    echo "<br> ------------ Testing test_updateQuota";

    $db_ctl = new db_manager();
    $db_ctl->resetDB();

    $db = $db_ctl->getDB();

    $a1 = toArr("Colin", "Colin/", TRUE, "Waugh", "*****", $_SERVER['REMOTE_ADDR']);
 
    $person1 = PersonObj::newPerson($a1);

    $db->insert($person1);

    $r1 = $db->getByID($person1->getID());

    assert($r1->getID() == $person1->getID());

    $person1->addQuota(100000);

    $db->updateQuota($person1);

    $r1 = $db->getByID($person1->getID());

    assert($r1->getQuota() == 100000);

    echo "<br> ------------ PASSED ";

}

function test_addQuota() {
    echo "<br> ------------ Testing test_addQuota";

    $db_ctl = new db_manager();
    $db_ctl->resetDB();

    $db = $db_ctl->getDB();

    $a1 = toArr("Colin", "Colin/", TRUE, "Waugh", "*****", $_SERVER['REMOTE_ADDR']);
 
    $person1 = PersonObj::newPerson($a1);

    assert($person1->addQuota(100000));

    assert(!$person1->addQuota(10000000));

    echo "<br> ------------ PASSED ";

}

function testDeleteAll() {

    echo "<br> ------------ Testing deleteAll";

    $db_ctl = new db_manager();
    $db_ctl->resetDB();

    $db = $db_ctl->getDB();



    $a1 = toArr("Colin", "Colin/", TRUE, "Waugh", "*****", $_SERVER['REMOTE_ADDR']);
    $a2 = toArr("Colin2", "Colin2/", TRUE, "Waugh2", "******", $_SERVER['REMOTE_ADDR']);
    $a3 = toArr("Colin3", "Colin3/", FALSE, "Waugh3", "******", $_SERVER['REMOTE_ADDR']);
    
    $person1 = PersonObj::newPerson($a1);
    $person2 = PersonObj::newPerson($a2);
    $person3 = PersonObj::newPerson($a3);

    $db->insert($person1);
    $db->insert($person2);
    $db->insert($person3);

    $count = $db->userCount();
    assert($count == 3, "USER COUNT ERROR");
    echo "<br>" . "3
     = $count";

    $db->deleteAll();

    $count = $db->userCount();
    assert('$count == 0', "USER COUNT ERROR");
    echo "<br>" . "0 = $count";

    // $db_ctl->resetDB();
}

function testDeleteByID() {

    echo "<br> ------------ Testing testDeleteByID";

    $db_ctl = new db_manager();
    $db_ctl->resetDB();

    $db = $db_ctl->getDB();

    $a1 = toArr("Colin", "Colin/", TRUE, "Waugh", "*****", $_SERVER['REMOTE_ADDR']);
    $a2 = toArr("Colin2", "Colin2/", TRUE, "Waugh2", "******", $_SERVER['REMOTE_ADDR']);
    $a3 = toArr("Colin3", "Colin3/", FALSE, "Waugh3", "******", $_SERVER['REMOTE_ADDR']);
    
    $person1 = PersonObj::newPerson($a1);
    $person2 = PersonObj::newPerson($a2);
    $person3 = PersonObj::newPerson($a3);

    $db->insert($person1);
    $db->insert($person2);
    $db->insert($person3);

    $count = $db->userCount();
    assert($count == 3, "USER COUNT ERROR");
    echo "<br>" . "3 = $count";

    $db->delete($person2->getID());

    $count = $db->userCount();
    assert($count == 2, "USER COUNT ERROR");
    echo "<br>" . "2 = $count";

    $db->delete($person1->getID());
    $db->delete($person3->getID());

    $count = $db->userCount();
    assert($count == 0, "USER COUNT ERROR");
    echo "<br>" . "0 = $count";

}

function testGetByID() {

    echo "<br> ------------ Testing testGetByID";

    $db_ctl = new db_manager();
    $db_ctl->resetDB();

    $db = $db_ctl->getDB();

    $a1 = toArr("Colin", "Colin/", TRUE, "Waugh", "*****", $_SERVER['REMOTE_ADDR']);
    $a2 = toArr("Colin2", "Colin2/", TRUE, "Waugh2", "******", $_SERVER['REMOTE_ADDR']);
    $a3 = toArr("Colin3", "Colin3/", FALSE, "Waugh3", "******", $_SERVER['REMOTE_ADDR']);
    
    $person1 = PersonObj::newPerson($a1);
    $person2 = PersonObj::newPerson($a2);
    $person3 = PersonObj::newPerson($a3);

    $db->insert($person1);
    $db->insert($person2);
    $db->insert($person3);

    $r1 = $db->getByID($person1->getID());
    $r2 = $db->getByID($person2->getID());
    $r3 = $db->getByID($person3->getID());

    assert(isset($r1), "testGetById r1 != null");
    assert(isset($r2), "testGetById r2 != null");
    assert(isset($r3), "testGetById r3 != null");

    assert($r1->getID() == $person1->getID(), "CHECKING getByID(0) == 0");
    assert($r2->getID() == $person2->getID(), "CHECKING getByID(1) == 1");
    assert($r3->getID() == $person3->getID(), "CHECKING getByID(2) == 2");

    $count = $db->userCount();
    echo "<br>" . "COUNT = $count";
    // $db_ctl->resetDB();
}

function testInsert() {

    echo "<br> ------------ Testing testInsert ";

    $db_ctl = new db_manager();
    $db_ctl->resetDB();

    $db = $db_ctl->getDB();

    $a1 = toArr("Colin", "Colin/", TRUE, "Waugh", "*****", $_SERVER['REMOTE_ADDR']);
    $a2 = toArr("Colin2", "Colin2/", FALSE, "Waugh2", "******", $_SERVER['REMOTE_ADDR']);
    $a3 = toArr("Colin3", "Colin3/", FALSE, "Waugh3", "******", $_SERVER['REMOTE_ADDR']);
    
    $person = PersonObj::newPerson($a1);
    $person2 = PersonObj::newPerson($a2);
    $person3 = PersonObj::newPerson($a3);

    $db->insert($person);
    $db->insert($person2);
    $db->insert($person3);

    $persons = $db->getAllPublic();

    echo "<br> Should be two printed";

    foreach ($persons as $curr) {
        echo $curr;
    }

}

function test_personObj() {

    $person = new PersonObj("Colin", "Colin/", TRUE, "Waugh", "*****", $_SERVER['REMOTE_ADDR']);
    echo "<br>" . $person;    
}

?>