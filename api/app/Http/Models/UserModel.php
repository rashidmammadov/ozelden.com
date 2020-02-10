<?php


class UserModel {

    private $identifier;
    private $type;
    private $name;
    private $surname;
    private $birthday;
    private $email;
    private $sex;
    private $state;
    private $rememberToken;
    private $oneSignalDeviceId;

    public function __construct($parameters = null) {
        $this->setIdentifier($parameters[IDENTIFIER]);
        $this->setType($parameters[TYPE]);
        $this->setName($parameters[NAME]);
        $this->setSurname($parameters[SURNAME]);
        $this->setBirthday($parameters[BIRTHDAY]);
        $this->setEmail($parameters[EMAIL]);
        $this->setSex($parameters[SEX]);
        $this->setState($parameters[STATE]);
        $this->setRememberToken($parameters[REMEMBER_TOKEN]);
        $this->setOneSignalDeviceId($parameters[ONESIGNAL_DEVICE_ID]);
    }

    public function get() {
        return array(
            IDENTIFIER => $this->getIdentifier(),
            TYPE => $this->getType(),
            NAME => $this->getName(),
            SURNAME => $this->getSurname(),
            BIRTHDAY => $this->getBirthday(),
            EMAIL => $this->getEmail(),
            SEX => $this->getSex(),
            STATE => $this->getState(),
            REMEMBER_TOKEN => $this->getRememberToken(),
            ONESIGNAL_DEVICE_ID => $this->getOneSignalDeviceId()
        );
    }

    public function getIdentifier() {
        return $this->identifier;
    }

    public function setIdentifier($identifier) {
        $this->identifier = $identifier;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getSurname() {
        return $this->surname;
    }

    public function setSurname($surname) {
        $this->surname = $surname;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSex() {
        return $this->sex;
    }

    public function setSex($sex) {
        $this->sex = $sex;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getRememberToken() {
        return $this->rememberToken;
    }

    public function setRememberToken($rememberToken) {
        $this->rememberToken = $rememberToken;
    }

    public function getOneSignalDeviceId() {
        return $this->oneSignalDeviceId;
    }

    public function setOneSignalDeviceId($oneSignalDeviceId) {
        $this->oneSignalDeviceId = $oneSignalDeviceId;
    }

}
