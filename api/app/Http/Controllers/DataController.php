<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\DataQuery;

class DataController extends ApiController {

    /**
     * Handle request to get data with given type.
     * @param string $type - holds the data type.
     * @return mixed
     */
    public function getData(string $type) {
        $result = null;
        if ($type == 'cities') {
            $result = $this->prepareCities();
        }
        return $this->respond($result);
    }

    /**
     * Prepare cities and relative districts.
     * @return array - returns prepared data.
     */
    private function prepareCities() {
        $queryResult = DataQuery::getCities();
        $cityAndDistricts = array();
        foreach ($queryResult as $city => $districts) {
            $districtArray = array();
            foreach ($districts as $district) {
                array_push($districtArray, $district[DISTRICT_NAME]);
            }
            array_push($cityAndDistricts, array(CITY_NAME => $city, DISTRICTS => $districtArray));
        }
        return $cityAndDistricts;
    }

}
