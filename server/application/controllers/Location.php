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
}
?>