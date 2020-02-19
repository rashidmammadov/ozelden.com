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
        } else if ($type == 'lectures') {
            $result = $this->prepareLectures();
        }
        return $this->respondCreated('', $result);
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

    /**
     * Prepare lectures and relative lecture themes.
     * @return array - returns prepared data.
     */
    private function prepareLectures() {
        $queryResult = DataQuery::getLectures();
        $lectures = array();
        foreach ($queryResult as $lectureArea => $lectureThemes) {
            $lectureThemesArray = array();
            foreach ($lectureThemes as $lectureTheme) {
                array_push($lectureThemesArray,
                    array(LECTURE_THEME => $lectureTheme[LECTURE_THEME], AVERAGE_TRY => $lectureTheme[AVERAGE_TRY])
                );
            }
            array_push($lectures, array(LECTURE_AREA => $lectureArea, LECTURE_THEMES => $lectureThemesArray));
        }
        return $lectures;
    }

}
