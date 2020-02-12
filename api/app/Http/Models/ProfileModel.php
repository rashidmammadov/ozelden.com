<?php

namespace App\Http\Models;

class ProfileModel {

    private $user_id;
    private $picture;
    private $phone;
    private $country;
    private $city;
    private $district;
    private $address;
    private $language;

    public function __construct($parameters = null) {
        $this->setUserId($parameters[USER_ID]);
        $this->setPicture($parameters[PICTURE]);
        $this->setPhone($parameters[PHONE]);
        $this->setCountry($parameters[COUNTRY]);
        $this->setCity($parameters[CITY]);
        $this->setDistrict($parameters[DISTRICT]);
        $this->setAddress($parameters[ADDRESS]);
        $this->setLanguage($parameters[LANGUAGE]);
    }

    public function get() {
        return array(
            USER_ID => $this->getUserId(),
            PICTURE => $this->getPicture(),
            PHONE => $this->getPhone(),
            COUNTRY => $this->getCountry(),
            CITY => $this->getCity(),
            DISTRICT => $this->getDistrict(),
            ADDRESS => $this->getAddress(),
            LANGUAGE => $this->getLanguage()
        );
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function setUserId($user_id): void {
        $this->user_id = $user_id;
    }


    public function getPicture() {
        return $this->picture;
    }

    public function setPicture($picture): void {
        $this->picture = $picture;
    }

    public function getPhone() {
        return $this->phone;
    }

    public function setPhone($phone): void {
        $this->phone = $phone;
    }

    public function getCountry() {
        return $this->country;
    }

    public function setCountry($country): void {
        $this->country = $country;
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

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address): void {
        $this->address = $address;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function setLanguage($language): void {
        $this->language = $language;
    }

}
