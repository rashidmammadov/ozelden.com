<?php


namespace App\Http\Models;


class SuitableFacilityModel {

    private $demo;
    private $groupDiscount;
    private $packageDiscount;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setDemo($parameters[DEMO]);
            $this->setGroupDiscount($parameters[GROUP_DISCOUNT]);
            $this->setPackageDiscount($parameters[PACKAGE_DISCOUNT]);
        }
    }

    public function get() {
        return array(
            DEMO => $this->getDemo(),
            GROUP_DISCOUNT => $this->getGroupDiscount(),
            PACKAGE_DISCOUNT => $this->getPackageDiscount()
        );
    }

    public function getDemo() {
        return !!$this->demo;
    }

    public function setDemo($demo): void {
        $this->demo = $demo;
    }

    public function getGroupDiscount() {
        return !!$this->groupDiscount;
    }

    public function setGroupDiscount($groupDiscount): void {
        $this->groupDiscount = $groupDiscount;
    }

    public function getPackageDiscount() {
        return !!$this->packageDiscount;
    }

    public function setPackageDiscount($packageDiscount): void {
        $this->packageDiscount = $packageDiscount;
    }

}
