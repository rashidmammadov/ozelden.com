<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DataLibrary{

    public function getLectures() {
        $file = fopen("data/lecturesTurkey.json", "r") or die("Unable to open file!");
        $lectures = fread($file,filesize("data/lecturesTurkey.json"));

        return json_decode($lectures);
        fclose($file);
    }
}
?>