<?php

Class Location extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->library(DATA_LIBRARY);
        $this->load->library(TUTOR_LIBRARY);
    }

    public function getCities() {
        $file = fopen("data/citiesTurkey.json", "r") or die("Unable to open file!");
        $cities = fread($file,filesize("data/citiesTurkey.json"));

        $result = array(
            'status' => 'success',
            'cities' => json_decode($cities)
        );

        echo json_encode($result);
        fclose($file);
    }

    public function getLectures() {
        $lectures = $this->datalibrary->getLectures();

        $result = array(
            'status' => 'success',
            'lectures' => $lectures
        );
        echo json_encode($result);
    }
}
?>