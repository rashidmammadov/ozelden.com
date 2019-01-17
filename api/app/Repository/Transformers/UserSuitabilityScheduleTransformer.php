<?php

namespace App\Repository\Transformers;

class UserSuitabilityScheduleTransformer extends Transformer{

    public function transform($schedule){
        return [
            REGION => json_decode($schedule->region),
            LOCATION => json_decode($schedule->location),
            COURSE_TYPE => json_decode($schedule->courseType),
            FACILITY => json_decode($schedule->facility),
            DAY_HOUR_TABLE => json_decode($schedule->dayHourTable)
        ];
    }

    public function setCourseType() {
        $courseType = array(
            INDIVIDUAL => false,
            GROUP => false,
            CLASS_ => false );
        return $courseType;
    }

    public function setDayHourTable() {
        $hour = array( '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0,
            '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0, '24' => 0 );
        $dayHourTable = array( '1' => $hour, '2' => $hour,'3' => $hour,'4' => $hour,'5' => $hour,'6' => $hour,'7' => $hour );
        return $dayHourTable;
    }

    public function setFacility() {
        $facility = array(
            DEMO => false,
            GROUP_DISCOUNT => false,
            PACKAGE_DISCOUNT => false );
        return $facility;
    }

    public function setRegion() {
        $region = array();
        return $region;
    }

    public function setLocation() {
        $location = array(
            STUDENT_HOME => false,
            TUTOR_HOME => false,
            ETUDE => false,
            COURSE => false,
            LIBRARY => false,
            OVER_INTERNET => false );
        return $location;
    }

}
