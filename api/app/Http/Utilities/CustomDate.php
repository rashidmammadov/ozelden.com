<?php

namespace App\Http\Utilities;

class CustomDate {

    public static $oneDay = 86400000;
    public static $threeDays = 259200000;
    public static $oneWeek = 604800000;
    public static $fifteenWeek = 1296000000;
    public static $oneMonth = 2592000000;

    public static function currentMilliseconds() {
        return round(microtime(true) * 1000);
    }

    public static function millisecondsToDate($date = null, $format = null) {
        if ($date) {
            if ($format == DATE) {
                $result = date(DAY_MONTH, $date / 1000);
            } else if ($format == TIME) {
                $result = date(HOUR_MINUTE, $date / 1000);
            } else if ($format == DATE_TIME) {
                $result = date(DATE_FORMAT, $date / 1000);
            } else {
                $result = date(DATE_FORMAT_COMPACT, $date / 1000);
            }
        } else {
            if ($format == DATE) {
                $result = date(DAY_MONTH);
            } else if ($format == TIME) {
                $result = date(HOUR_MINUTE);
            } else if ($format == DATE_TIME) {
                $result = date(DATE_FORMAT);
            } else {
                $result = date(DATE_FORMAT_COMPACT);
            }
        }
        return $result;
    }

    public static function dateToMillisecond($date) {
        return strtotime($date) * 1000;
    }

}
