<?php

namespace App\Http\Models;

class FinanceModel {

    private $financeId;
    private $userId;
    private $referenceCode;
    private $item;
    private $price;
    private $priceWithCommission;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setFinanceId($parameters[FINANCE_ID]);
            $this->setUserId($parameters[USER_ID]);
            $this->setReferenceCode($parameters[REFERENCE_CODE]);
            $this->setItem($parameters[ITEM]);
            $this->setPrice($parameters[PRICE]);
            $this->setPriceWithCommission($parameters[PRICE_WITH_COMMISSION]);
        }
    }

    public function get() {
        return array(
            FINANCE_ID => $this->getFinanceId(),
            USER_ID => $this->getUserId(),
            REFERENCE_CODE => $this->getReferenceCode(),
            ITEM => $this->getItem(),
            PRICE => $this->getPrice(),
            PRICE_WITH_COMMISSION => $this->getPriceWithCommission()
        );
    }

    public function getFinanceId() {
        return $this->financeId;
    }

    public function setFinanceId($financeId): void {
        $this->financeId = $financeId;
    }

    public function getUserId() {
        return $this->userId;
    }

    public function setUserId($userId): void {
        $this->userId = $userId;
    }

    public function getReferenceCode() {
        return $this->referenceCode;
    }

    public function setReferenceCode($referenceCode): void {
        $this->referenceCode = $referenceCode;
    }

    public function getItem() {
        return $this->item;
    }

    public function setItem($item): void {
        $this->item = $item;
    }

    public function getPrice() {
        return $this->price;
    }

    public function setPrice($price): void {
        $this->price = $price;
    }

    public function getPriceWithCommission() {
        return $this->priceWithCommission;
    }

    public function setPriceWithCommission($priceWithCommission): void {
        $this->priceWithCommission = $priceWithCommission;
    }

}
