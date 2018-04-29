<?php

Class Location extends CI_Controller{

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
        $file = fopen("data/lecturesTurkey.json", "r") or die("Unable to open file!");
        $lectures = fread($file,filesize("data/lecturesTurkey.json"));

        $result = array(
            'status' => 'success',
            'lectures' => json_decode($lectures)
        );

        echo json_encode($result);
        fclose($file);
    }
}
?>