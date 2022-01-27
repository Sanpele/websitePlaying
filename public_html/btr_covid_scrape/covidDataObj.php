<?php

class CovidData {

    public $id; // Unique identifer each day so we don't have repeated values for date.
    public $date; // String date. YYYY-MM-DD
    public $prov_test_rate; // provincial test rate %
    public $wpg_test_rate; // winnipeg test rate %
    public $todays_cases; // number of new cases recorded today

    public function __construct($id, $date, $prov, $wpg, $today) {
        $this->id = $id;
        $this->date = $date;
        $this->prov_test_rate = $prov;
        $this->wpg_test_rate = $wpg;
        $this->todays_cases = $today;
    }

    public function __toString() {
        $out = "id				: " . $this->id . "<br>" . 
		"provincial rate	: " . $this->prov_test_rate . "<br>" . 
		"wpg rate			: " . $this->wpg_test_rate . "<br>" . 
		"todays cases		: " . $this->todays_cases . "<br>" .
        "date           	: " . $this->date . "<br>";
        return $out;
    }
}

?>