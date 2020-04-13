<?php

namespace App\Http\Models;

class AverageModel {

    private $rankingAvg;
    private $experienceAvg;
    private $priceAvg;
    private $offersCount;
    private $studentsCount;
    private $registerDate;

    public function __construct($parameters = null) {
        if ($parameters) {
            $this->setRankingAvg($parameters[RANKING_AVG]);
            $this->setExperienceAvg($parameters[EXPERIENCE_AVG]);
            $this->setPriceAvg($parameters[PRICE_AVG]);
        }
    }

    public function get() {
        return array(
            RANKING_AVG => $this->getRankingAvg(),
            EXPERIENCE_AVG => $this->getExperienceAvg(),
            PRICE_AVG => $this->getPriceAvg(),
            OFFERS_COUNT => $this->getOffersCount(),
            STUDENTS_COUNT => $this->getStudentsCount(),
            REGISTER_DATE => $this->getRegisterDate()
        );
    }

    public function getRankingAvg() {
        return $this->rankingAvg;
    }

    public function setRankingAvg($rankingAvg): void {
        $this->rankingAvg = $rankingAvg;
    }

    public function getExperienceAvg() {
        return $this->experienceAvg;
    }

    public function setExperienceAvg($experienceAvg): void {
        $this->experienceAvg = $experienceAvg;
    }

    public function getPriceAvg() {
        return $this->priceAvg;
    }

    public function setPriceAvg($priceAvg): void {
        $this->priceAvg = $priceAvg;
    }


    public function getOffersCount() {
        return $this->offersCount;
    }

    public function setOffersCount($offersCount): void {
        $this->offersCount = $offersCount;
    }

    public function getStudentsCount() {
        return $this->studentsCount;
    }

    public function setStudentsCount($studentsCount): void {
        $this->studentsCount = $studentsCount;
    }

    public function getRegisterDate() {
        return $this->registerDate;
    }

    public function setRegisterDate($registerDate): void {
        $this->registerDate = $registerDate;
    }

}
