<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;

class DataController extends ApiController
{
    /**
     * DataController constructor.
     */
    public function __construct() {}

    /**
     * @description: Returns data.
     * @param Request $request
     * @return json: User info
     */
    public function get(Request $request) {
        $data = json_decode("{}");
        if ($request['lectures'] == 'true') {
            $lectures = json_decode($this->_readLectures());
            $data->lectures = $lectures;
        }
        if ($request['regions'] == 'true') {
            $regions = json_decode($this->_readRegions());
            $data->regions = $regions;
        }
        return $this->respondCreated("SELECTED_DATA_RETURNED_SUCCESSFULLY", $data);
    }

    /**
     * @description open and return lectures file.
     * @return mixed
     */
    private function _readLectures() {
        $path = storage_path().'/lecturesTurkey.json';
        return File::get($path);
    }

    /**
     * @description open and return regions file.
     * @return mixed
     */
    private function _readRegions() {
        $path = storage_path().'/citiesTurkey.json';
        return File::get($path);
    }
}
