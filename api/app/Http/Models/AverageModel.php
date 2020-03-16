<?php

namespace App\Http\Models;

class AverageModel {

    private $rankingAvg;
    private $experienceAvg;
    private $priceAvg;

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
            PRICE_AVG => $this->getPriceAvg()
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

}
