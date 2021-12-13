<?php

class CovidData {

    public $id;
    public $date;
    public $prov_test_rate;
    public $wpg_test_rate;
    public $todays_cases;

    public function __construct($id, $date, $prov, $wpg, $today) {
        $this->id = $id;
        $this->date = $date;
        $this->prov_test_rate = $prov;
        $this->wpg_test_rate = $wpg;
        $this->todays_cases = $today;
    }

    public function __toString() {
        $out = "id				: " . $this->id . "<br>" . 
        "date           	: " . $this->date . "<br>" . 
		"provincial rate	: " . $this->prov_test_rate . "<br>" . 
		"wpg rate			: " . $this->wpg_test_rate . "<br>" . 
		"todays cases		: " . $this->todays_cases . "<br>";
        return $out;
    }
}

?>