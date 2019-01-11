<?php

namespace App\Repository\Transformers;

class UserSuitabilityScheduleTransformer extends Transformer{

    public function transform($schedule){
        return [
            'region' => json_decode($schedule->region),
            'location' => json_decode($schedule->location),
            'courseType' => json_decode($schedule->courseType),
            'facility' => json_decode($schedule->facility),
            'dayHourTable' => json_decode($schedule->dayHourTable)
        ];
    }

    public function setCourseType() {
        $courseType = array(
            'individual' => false,
            'group' => false,
            'class' => false);
        return $courseType;
    }

    public function setDayHourTable() {
        $hour = array( '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0,
            '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0, '24' => 0 );
        $dayHourTable = array( '1' => $hour, '2' => $hour,'3' => $hour,'4' => $hour,'5' => $hour,'6' => $hour,'7' => $hour );
        return $dayHourTable;
    }

    public function setFacility() {
        $facility = array( 'demo' => false,
            'groupDiscount' => false,
            'packageDiscount' => false );
        return $facility;
    }

    public function setRegion() {
        $region = array();
        return $region;
    }

    public function setLocation() {
        $location = array(
            'studentHome' => false,
            'tutorHome' => false,
            'etude' => false,
            'course' => false,
            'library' => false,
            'overInternet' => false );
        return $location;
    }

}
