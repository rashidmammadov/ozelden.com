<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TutorLibrary {

    public function prepareSuitabilitySchedule($data) {
        if($data->region == null || $data->region == 'null'){
            $data->region = array();
        }else{
            $data->region = json_decode($data->region);
        }

        if($data->location == null || $data->location == 'null'){
            $location = array(
                STUDENT_HOME => false,
                TUTOR_HOME => false,
                ETUDE => false,
                COURSE => false,
                LIBRARY => false,
                OVER_INTERNET => false
            );
            $data->location = $location;
        }else{
            $data->location = json_decode($data->location);
        }

        if($data->courseType == null || $data->courseType == 'null'){
            $courseType = array(
                INDIVIDUAL => false,
                GROUP => false,
                CLASS_ => false
            );
            $data->courseType = $courseType;
        }else{
            $data->courseType = json_decode($data->courseType);
        }

        if($data->facility == null || $data->facility == 'null'){
            $facility = array(
                DEMO => false,
                GROUP_DISCOUNT => false,
                PACKAGE_DISCOUNT => false
            );
            $data->facility = $facility;
        }else{
            $data->facility = json_decode($data->facility);
        }

        if($data->dayHourTable == null || $data->dayHourTable == 'null'){
            $hour = array(
                '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0, '13' => 0, '14' => 0, '15' => 0,
                '16' => 0, '17' => 0, '18' => 0, '19' => 0, '20' => 0, '21' => 0, '22' => 0, '23' => 0, '24' => 0
            );

            $dayHourTable = array(
                '1' => $hour, '2' => $hour,'3' => $hour,'4' => $hour,'5' => $hour,'6' => $hour,'7' => $hour
            );

            $data->dayHourTable = $dayHourTable;
        }else{
            $data->dayHourTable = json_decode($data->dayHourTable);
        }

        return $data;
    }
}