<?php

namespace App\Http\Utilities;

class Packages {

    private static $packages = array(
        BID => array(
            array(KEY => 'BID1', PRICE => 0.75, VALUE => 1, GROUP => 'BID'),
            array(KEY => 'BID3', PRICE => 1.5, VALUE => 3, GROUP => 'BID'),
            array(KEY => 'BID5', PRICE => 2.5, VALUE => 5, GROUP => 'BID'),
            array(KEY => 'BID10', PRICE => 4, VALUE => 10, GROUP => 'BID'),
            array(KEY => 'BID30', PRICE => 10, VALUE => 30, GROUP => 'BID'),
            array(KEY => 'BID50', PRICE => 15, VALUE => 50, GROUP => 'BID'),
            array(KEY => 'BID100', PRICE => 25, VALUE => 100, GROUP => 'BID')
        ),
        BOOST => array(
            array(KEY => 'BOOST1', PRICE => 1.5, VALUE => 1, GROUP => 'BOOST'),
            array(KEY => 'BOOST3', PRICE => 3, VALUE => 3, GROUP => 'BOOST'),
            array(KEY => 'BOOST7', PRICE => 4.5, VALUE => 7, GROUP => 'BOOST'),
            array(KEY => 'BOOST15', PRICE => 6, VALUE => 15, GROUP => 'BOOST'),
            array(KEY => 'BOOST30', PRICE => 8, VALUE => 30, GROUP => 'BOOST'),
            array(KEY => 'BOOST180', PRICE => 30, VALUE => 180, GROUP => 'BOOST'),
            array(KEY => 'BOOST365', PRICE => 45, VALUE => 365, GROUP => 'BOOST')
        ),
        RECOMMEND => array(
            array(KEY => 'RECOMMEND1', PRICE => 3, VALUE => 1, GROUP => 'RECOMMEND'),
            array(KEY => 'RECOMMEND3', PRICE => 6, VALUE => 3, GROUP => 'RECOMMEND'),
            array(KEY => 'RECOMMEND7', PRICE => 9, VALUE => 7, GROUP => 'RECOMMEND'),
            array(KEY => 'RECOMMEND15', PRICE => 12, VALUE => 15, GROUP => 'RECOMMEND'),
            array(KEY => 'RECOMMEND30', PRICE => 16, VALUE => 30, GROUP => 'RECOMMEND'),
            array(KEY => 'RECOMMEND180', PRICE => 60, VALUE => 180, GROUP => 'RECOMMEND'),
            array(KEY => 'RECOMMEND365', PRICE => 90, VALUE => 365, GROUP => 'RECOMMEND')
        )
    );

    public static function getAllPackages() {
        return self::$packages;
    }

    public static function getPackage(string $key) {
        $result = array();
        foreach (self::$packages as $packageName => $packageList) {
            foreach($packageList as $package) {
                if ($package[KEY] === $key) {
                    $result = $package;
                }
            }
        }
        return $result;
    }
}
