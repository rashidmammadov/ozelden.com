<?php

namespace App\Http\Controllers;

use App\Http\Models\AverageModel;
use App\Http\Models\SearchModel;
use App\Http\Models\SuitableRegionModel;
use App\Http\Models\TutorLectureModel;
use App\Http\Queries\MySQL\SearchQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends ApiController {

    /**
     * Handle request to get search result.
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request) {
        if ($request[SEARCH_TYPE] == TUTOR) {
            return $this->getTutorSearch($request);
        }
    }

    /**
     * Prepare tutor search result.
     * @param Request $request
     * @return mixed
     */
    private function getTutorSearch(Request $request) {
        $searchResultsFromDB = SearchQuery::searchTutor($request, 20);
        if ($searchResultsFromDB) {
            $data = array();
            foreach ($searchResultsFromDB->items() as $item) {
                $tutorId = $item[TUTOR_ID];
                if ($tutorId) {
                    $tutorConnectionsFromDB = SearchQuery::getTutorConnections($tutorId, $request);
                    if ($tutorConnectionsFromDB && count($tutorConnectionsFromDB)) {
                        $searchResult = new SearchModel($tutorConnectionsFromDB[0]);

                        $average = new AverageModel($tutorConnectionsFromDB[0]);
                        $searchResult->setAverage($average->get());

                        $lectures = $this->getTutorLectures($tutorConnectionsFromDB);
                        $searchResult->setLectures($lectures);

                        $regions = $this->getTutorRegions($tutorConnectionsFromDB);
                        $searchResult->setRegions($regions);

                        array_push($data, $searchResult->get());
                    }
                }
            }
            return $this->respondWithPagination('', $searchResultsFromDB, $data);
        } else {
            return $this->respondWithError(NO_RESULT_TO_SHOW);
        }
    }

    /**
     * @param $tutorConnectionsFromDB
     * @return array
     */
    private function getTutorLectures($tutorConnectionsFromDB): array {
        $lectures = array();
        $lecturesList = $tutorConnectionsFromDB->groupBy([LECTURE_AREA, LECTURE_THEME]);
        foreach ($lecturesList as $lectureArea => $lectureThemes) {
            foreach ($lectureThemes as $lectureTheme => $value) {
                $lecture = new TutorLectureModel($value[0]);
                array_push($lectures, $lecture->get());
            }
        }
        return $lectures;
    }

    /**
     * @param $tutorConnectionsFromDB
     * @return array
     */
    private function getTutorRegions($tutorConnectionsFromDB): array {
        $regions = array();
        $regionsList = $tutorConnectionsFromDB->groupBy([CITY, DISTRICT]);
        foreach ($regionsList as $city => $districts) {
            foreach ($districts as $district => $value) {
                $region = new SuitableRegionModel($value[0]);
                array_push($regions, $region->get());
            }
        }
        return $regions;
    }

}
