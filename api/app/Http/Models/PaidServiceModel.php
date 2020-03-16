<?php

namespace App\Http\Models;

use App\Http\Queries\MySQL\FinanceQuery;
use App\Http\Queries\MySQL\PaidServiceQuery;
use App\Http\Utilities\CustomDate;
use App\Http\Utilities\Packages;

class PaidServiceModel {

    private $bid;
    private $boost;
    private $recommend;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setBid($parameters[BID]);
            $this->setBoost($parameters[BOOST]);
            $this->setRecommend($parameters[RECOMMEND]);
        }
    }

    public function get() {
        return array(
            BID => $this->getBid(),
            BOOST => $this->getBoost(),
            RECOMMEND => $this->getRecommend()
        );
    }

    public function getBid() {
        return $this->bid;
    }

    public function setBid($bid): void {
        $this->bid = $bid;
    }

    public function getBoost() {
        return $this->boost;
    }

    public function setBoost($boost): void {
        $this->boost = $boost;
    }

    public function getRecommend() {
        return $this->recommend;
    }

    public function setRecommend($recommend): void {
        $this->recommend = $recommend;
    }

    public function upgradePaidService($userId, $paymentItems) {
        $paidServiceFromDB = PaidServiceQuery::get($userId);
        $paidServiceModel = new PaidServiceModel($paidServiceFromDB);
        foreach ($paymentItems as $paymentItem) {
            $package = Packages::getPackage($paymentItem->getItemId());
            if ($package[GROUP] == 'BID') {
                $updatedBid = intval($paidServiceModel->getBid()) + intval($package[VALUE]);
                $paidServiceModel->setBid($updatedBid);
            } else if ($package[GROUP] == 'BOOST') {
                $currentBoost = $paidServiceModel->getBoost();
                $additionalBoost = $package[VALUE] * CustomDate::$oneDay;
                if ($currentBoost) {
                    $updatedBoost = intval($currentBoost) + intval($additionalBoost);
                } else {
                    $updatedBoost = CustomDate::currentMilliseconds() + intval($additionalBoost);
                }
                $paidServiceModel->setBoost($updatedBoost);
            } else if ($package[GROUP] == 'RECOMMEND') {
                $currentRecommend = $paidServiceModel->getRecommend();
                $additionalRecommend = $package[VALUE] * CustomDate::$oneDay;
                if ($currentRecommend) {
                    $updatedRecommend = intval($currentRecommend) + intval($additionalRecommend);
                } else {
                    $updatedRecommend = CustomDate::currentMilliseconds() + intval($additionalRecommend);
                }
                $paidServiceModel->setRecommend($updatedRecommend);
            }
            $finance = new FinanceModel();
            $finance->setUserId($userId);
            $finance->setReferenceCode($paymentItem->getPaymentTransactionId());
            $finance->setItem($paymentItem->getItemId());
            $finance->setPrice($paymentItem->getPrice());
            $finance->setPriceWithCommission($paymentItem->getMerchantPayoutAmount());
            FinanceQuery::save($finance->get());
        }
        PaidServiceQuery::update($userId, $paidServiceModel->get());
    }

}
