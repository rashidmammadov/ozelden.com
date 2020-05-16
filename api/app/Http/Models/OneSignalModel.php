<?php

namespace App\Http\Models;

class OneSignalModel {

    private $userId;
    private $oneSignalDeviceId;
    private $deviceType;
    private $ip;
    private $status;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setUserId($parameters[USER_ID]);
            $this->setOneSignalDeviceId($parameters[ONE_SIGNAL_DEVICE_ID]);
            $this->setDeviceType($parameters[DEVICE_TYPE]);
            $this->setIp($parameters[IP]);
            $this->setStatus($parameters[STATUS]);
        }
    }

    public function get() {
        return array(
            USER_ID => $this->getUserId(),
            ONE_SIGNAL_DEVICE_ID => $this->getOneSignalDeviceId(),
            DEVICE_TYPE => $this->getDeviceType(),
            IP => $this->getIp(),
            STATUS => $this->getStatus()
        );
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId): void {
        $this->userId = $userId;
    }

    public function getOneSignalDeviceId() {
        return $this->oneSignalDeviceId;
    }

    public function setOneSignalDeviceId($oneSignalDeviceId): void {
        $this->oneSignalDeviceId = $oneSignalDeviceId;
    }

    public function getDeviceType() {
        return $this->deviceType;
    }

    public function setDeviceType($deviceType): void {
        $this->deviceType = $deviceType;
    }

    public function getIp() {
        return $this->ip;
    }

    public function setIp($ip): void {
        $this->ip = $ip;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status): void {
        $this->status = $status;
    }

}
