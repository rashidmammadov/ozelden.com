<?php

namespace App\Http\Models;

class ProfileModel {

    private $user_id;
    private $picture;
    private $phone;
    private $profession;
    private $description;
    private $country;
    private $city;
    private $district;
    private $address;
    private $hangoutsAccount;
    private $skypeAccount;
    private $zoomAccount;
    private $language;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setUserId($parameters[USER_ID]);
            $this->setPicture($parameters[PICTURE]);
            $this->setPhone($parameters[PHONE]);
            $this->setProfession($parameters[PROFESSION]);
            $this->setDescription($parameters[DESCRIPTION]);
            $this->setCountry($parameters[COUNTRY]);
            $this->setCity($parameters[CITY]);
            $this->setDistrict($parameters[DISTRICT]);
            $this->setAddress($parameters[ADDRESS]);
            $this->setHangoutsAccount($parameters[HANGOUTS_ACCOUNT]);
            $this->setSkypeAccount($parameters[SKYPE_ACCOUNT]);
            $this->setZoomAccount($parameters[ZOOM_ACCOUNT]);
            $this->setLanguage($parameters[LANGUAGE]);
        }
    }

    public function get() {
        return array(
            USER_ID => $this->getUserId(),
            PICTURE => $this->getPicture(),
            PHONE => $this->getPhone(),
            PROFESSION => $this->getProfession(),
            DESCRIPTION => $this->getDescription(),
            COUNTRY => $this->getCountry(),
            CITY => $this->getCity(),
            DISTRICT => $this->getDistrict(),
            ADDRESS => $this->getAddress(),
            HANGOUTS_ACCOUNT => $this->getHangoutsAccount(),
            SKYPE_ACCOUNT => $this->getSkypeAccount(),
            ZOOM_ACCOUNT => $this->getZoomAccount(),
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

    public function getProfession() {
        return $this->profession;
    }

    public function setProfession($profession): void {
        $this->profession = $profession;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description): void {
        $this->description = $description;
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

    public function getHangoutsAccount() {
        return $this->hangoutsAccount;
    }

    public function setHangoutsAccount($hangoutsAccount): void {
        $this->hangoutsAccount = $hangoutsAccount;
    }

    public function getSkypeAccount() {
        return $this->skypeAccount;
    }

    public function setSkypeAccount($skypeAccount): void {
        $this->skypeAccount = $skypeAccount;
    }

    public function getZoomAccount() {
        return $this->zoomAccount;
    }

    public function setZoomAccount($zoomAccount): void {
        $this->zoomAccount = $zoomAccount;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function setLanguage($language): void {
        $this->language = $language;
    }

}
