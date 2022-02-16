<?php

class CovidData {

    public $id; // Unique identifer each day so we don't have repeated values for date.
    public $bulletin_date; // String date. YYYY-MM-DD
    public $scraped_date; // String date. YYYY-MM-DD
    public $prov_test_rate; // provincial test rate %
    public $wpg_test_rate; // winnipeg test rate %
    public $todays_cases; // number of new cases recorded today

    public function __construct($id, $bul_date, $t_date, $prov, $wpg, $today) {
        $this->id = $id;
        $this->bulletin_date = $bul_date;
        $this->scraped_date = $t_date;
        $this->prov_test_rate = $prov;
        $this->wpg_test_rate = $wpg;
        $this->todays_cases = $today;
    }
    
    public function __toString() {
        $out = "<tr>" . 
        "<td>" . $this->id . "</td>" . 
        "<td>" . $this->bulletin_date . "</td>" . 
        "<td>" . $this->scraped_date . "</td>" . 
        "<td>" . $this->prov_test_rate . "</td>" . 
        "<td>" . $this->wpg_test_rate . "</td>" . 
        "<td>" . $this->todays_cases . "</td>" . 
        "</tr>";    
        return $out;
    }
}

?>