<?php

namespace App\Http\Controllers;

use App\Http\Models\AverageModel;
use App\Http\Models\ExpectationModel;
use App\Http\Models\SearchModel;
use App\Http\Models\StudentModel;
use App\Http\Models\SuitableRegionModel;
use App\Http\Models\TutorLectureModel;
use App\Http\Queries\MySQL\SearchQuery;
use App\Http\Queries\MySQL\StudentQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class SearchController extends ApiController {

    /**
     * Handle request to get search result.
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request) {
        if ($request[SEARCH_TYPE] == TUTOR) {
            return $this->getTutorSearch($request);
        } else if ($request[SEARCH_TYPE] == TUTORED) {
            return $this->getTutoredSearch($request);
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
                $searchResult = $this->prepareTutorSearchResult($item, $request);
                array_push($data, $searchResult);
            }
            return $this->respondWithPagination('', $searchResultsFromDB, $data);
        } else {
            return $this->respondWithError(NO_RESULT_TO_SHOW);
        }
    }

    /**
     * Holds request to return search result of tutored.
     * @param Request $request
     * @return mixed
     */
    private function getTutoredSearch(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if ($user[TYPE] == TUTOR) {
                $searchResultsFromDB = SearchQuery::searchTutored($request, 20);
                if ($searchResultsFromDB) {
                    $data = array();
                    foreach ($searchResultsFromDB->items() as $item) {
                        $searchResult = new SearchModel($item);

                        $expectation = new ExpectationModel($item);
                        $searchResult->setExpectation($expectation->get());

                        $lectures = array();
                        $lecture = new TutorLectureModel($item);
                        array_push($lectures, $lecture->get());
                        $searchResult->setLectures($lectures);

                        $regions = array();
                        $region = new SuitableRegionModel($item);
                        array_push($regions, $region->get());
                        $searchResult->setRegions($regions);

                        if ($item[STUDENT_ID]) {
                            $studentFromDB = StudentQuery::getStudentById($item[STUDENT_ID]);
                            $student = new StudentModel($studentFromDB);
                            $searchResult->setStudent($student->get());
                        }

                        array_push($data, $searchResult->get());
                    }
                    return $this->respondWithPagination('', $searchResultsFromDB, $data);
                } else {
                    return $this->respondWithError(NO_RESULT_TO_SHOW);
                }
            } else {
                return $this->respondWithError(PERMISSION_DENIED);
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Handle request to get recommended tutors.
     * @param Request $request
     * @return mixed
     */
    public function getRecommendedTutors(Request $request) {
        $recommendedTutorsFromDB = SearchQuery::getRecommendedTutors($request);
        if ($recommendedTutorsFromDB) {
            $data = array();
            foreach ($recommendedTutorsFromDB as $recommendTutorFromDB) {
                $searchResult = $this->prepareTutorSearchResult($recommendTutorFromDB);
                array_push($data, $searchResult);
            }
            return $this->respondCreated('', $data);
        } else {
            return $this->respondWithError(NO_RESULT_TO_SHOW);
        }
    }

    /**
     * Prepare tutor`s search result from db.
     * @param $tutor - holds the tutor data.
     * @param null $request
     * @return array
     */
    private function prepareTutorSearchResult($tutor, $request = null) {
        $tutorId = $tutor[TUTOR_ID];
        if ($tutorId) {
            $tutorConnectionsFromDB = SearchQuery::getTutorConnections($tutorId, $request);
            if ($tutorConnectionsFromDB && count($tutorConnectionsFromDB)) {
                $searchResult = new SearchModel($tutorConnectionsFromDB[0]);

                !is_null($tutor[BOOST]) && $searchResult->setBoost(true);
                !is_null($tutor[RECOMMEND]) && $searchResult->setRecommend(true);

                $average = new AverageModel($tutorConnectionsFromDB[0]);
                $searchResult->setAverage($average->get());

                $lectures = $this->getTutorLectures($tutorConnectionsFromDB);
                $searchResult->setLectures($lectures);

                $regions = $this->getTutorRegions($tutorConnectionsFromDB);
                $searchResult->setRegions($regions);

                return $searchResult->get();
            }
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
