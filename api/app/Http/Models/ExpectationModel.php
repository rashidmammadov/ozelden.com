<?php

namespace App\Http\Models;

class ExpectationModel {

    private $minPrice;
    private $maxPrice;
    private $sex;

    public function __construct($parameters) {
        if ($parameters) {
            $this->setMinPrice($parameters[MIN_PRICE]);
            $this->setMaxPrice($parameters[MAX_PRICE]);
            $this->setSex($parameters[SEX]);
        }
    }

    public function get() {
        return array(
            MIN_PRICE => $this->getMinPrice(),
            MAX_PRICE => $this->getMaxPrice(),
            SEX => $this->getSex()
        );
    }

    public function getMinPrice() {
        return $this->minPrice;
    }

    public function setMinPrice($minPrice): void {
        $this->minPrice = $minPrice;
    }

    public function getMaxPrice() {
        return $this->maxPrice;
    }

    public function setMaxPrice($maxPrice): void {
        $this->maxPrice = $maxPrice;
    }

    public function getSex() {
        return $this->sex;
    }

    public function setSex($sex): void {
        $this->sex = $sex;
    }


}
