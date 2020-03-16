<?php


namespace App\Http\Models;


class SuitableRegionModel {

    private $city;
    private $district;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setCity($parameters[CITY]);
            $this->setDistrict($parameters[DISTRICT]);
        }
    }

    public function get() {
        return array(
            CITY => $this->getCity(),
            DISTRICT => $this->getDistrict()
        );
    }

    public function getCity() {
        return $this->city;
    }

    public function setCity($city): void {
        $this->city = $city;
    }

    public function getDistrict() {
        return $this->district;
    }

    public function setDistrict($district): void {
        $this->district = $district;
    }

}
