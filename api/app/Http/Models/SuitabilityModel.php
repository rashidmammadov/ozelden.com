<?php

namespace App\Http\Models;

class SuitabilityModel {

    private $courseType;
    private $facility;
    private $location;
    private $regions;

    public function __construct($parameters = null) {
        $this->setCourseType($parameters[COURSE_TYPE]);
        $this->setFacility($parameters[FACILITY]);
        $this->setLocation($parameters[LOCATION]);
        $this->setRegions($parameters[REGIONS]);
    }

    public function get() {
        return array(
            COURSE_TYPE => $this->getCourseType(),
            FACILITY => $this->getFacility(),
            LOCATION => $this->getLocation(),
            REGIONS => $this->getRegions()
        );
    }

    public function getCourseType() {
        return $this->courseType;
    }

    public function setCourseType($courseType): void {
        $this->courseType = $courseType;
    }

    public function getFacility() {
        return $this->facility;
    }

    public function setFacility($facility): void {
        $this->facility = $facility;
    }

    public function getLocation() {
        return $this->location;
    }

    public function setLocation($location): void {
        $this->location = $location;
    }

    public function getRegions() {
        return $this->regions;
    }

    public function setRegions($regions): void {
        $this->regions = $regions;
    }

}
