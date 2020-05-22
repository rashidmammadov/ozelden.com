<?php

namespace App\Http\Controllers;

use App\Http\Models\AnnouncementModel;
use App\Http\Models\AverageModel;
use App\Http\Models\ProfileModel;
use App\Http\Models\SuitabilityModel;
use App\Http\Models\SuitableCourseTypeModel;
use App\Http\Models\SuitableFacilityModel;
use App\Http\Models\SuitableLocationModel;
use App\Http\Models\SuitableRegionModel;
use App\Http\Models\TutorLectureModel;
use App\Http\Models\UserModel;
use App\Http\Models\UserProfileModel;
use App\Http\Queries\MySQL\AnnouncementQuery;
use App\Http\Queries\MySQL\OfferQuery;
use App\Http\Queries\MySQL\ProfileQuery;
use App\Http\Queries\MySQL\SuitabilityQuery;
use App\Http\Queries\MySQL\TutorLectureQuery;
use App\Http\Queries\MySQL\TutorStudentQuery;
use App\Http\Utilities\CustomDate;
use App\Http\Utilities\Picture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class ProfileController extends ApiController {

    /**
     * Get profile of current user from token.
     * @return mixed
     */
    public function getProfile() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user[IDENTIFIER];
            $profile = ProfileQuery::getProfileById($userId);
            if ($profile) {
                $profileModel = new ProfileModel($profile);
                return $this->respondCreated(null, $profileModel->get());
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Get profile of given user.
     * @param $id - holds the user id.
     * @return mixed
     */
    public function getProfileWithId($id) {
        $profileFromDB = ProfileQuery::getProfileConnections($id);
        if ($profileFromDB) {
            $profile = new UserProfileModel($profileFromDB);
            $currentUserId = null;
            try {
                $user = JWTAuth::parseToken()->authenticate();
                $currentUserId = $user[IDENTIFIER];
                $relation = TutorStudentQuery::checkRelationIfExist($id, $currentUserId);
                if ($relation) {
                    $profile->setPhone($profileFromDB[PHONE]);
                    $profile->setEmail($profileFromDB[EMAIL]);
                    $profile->setHangoutsAccount($profileFromDB[HANGOUTS_ACCOUNT]);
                    $profile->setSkypeAccount($profileFromDB[SKYPE_ACCOUNT]);
                    $profile->setZoomAccount($profileFromDB[ZOOM_ACCOUNT]);
                }
            } catch (JWTException $e) {
                Log::error($e->getMessage());
            }

            if ($profile->getType() == TUTORED) {
                $announcementsFromDB = AnnouncementQuery::get($id);
                if ($announcementsFromDB) {
                    $announcements = array();
                    foreach ($announcementsFromDB as $announcementFromDB) {
                        $announcement = new AnnouncementModel($announcementFromDB);
                        array_push($announcements, $announcement->get());
                    }
                    $profile->setTutoredAnnouncements($announcements);
                }
            } else if ($profile->getType() == TUTOR) {
                if ($profileFromDB[BOOST] && $profileFromDB[BOOST] > CustomDate::currentMilliseconds()) {
                    $profile->setBoost(true);
                }
                if ($profileFromDB[RECOMMEND] && $profileFromDB[RECOMMEND] > CustomDate::currentMilliseconds()) {
                    $profile->setRecommend(true);

                    $profile->setPhone($profileFromDB[PHONE]);
                    $profile->setEmail($profileFromDB[EMAIL]);
                    $profile->setHangoutsAccount($profileFromDB[HANGOUTS_ACCOUNT]);
                    $profile->setSkypeAccount($profileFromDB[SKYPE_ACCOUNT]);
                    $profile->setZoomAccount($profileFromDB[ZOOM_ACCOUNT]);
                }

                /** @var  $average */
                $average = new AverageModel();
                $offersCount = OfferQuery::getReceivedOffersCount($id);
                $studentsCount = TutorStudentQuery::getStudentsCount($id);

                $average->setPriceAvg($profileFromDB[PRICE_AVG]);
                $average->setExperienceAvg($profileFromDB[EXPERIENCE_AVG]);
                $average->setRankingAvg($profileFromDB[RANKING_AVG]);
                if ($offersCount) {
                    $average->setOffersCount($offersCount);
                }
                if ($studentsCount) {
                    $average->setStudentsCount($studentsCount);
                }
                $average->setRegisterDate(CustomDate::dateToMillisecond($profileFromDB[CREATED_AT]));
                $profile->setTutorStatistics($average->get());

                /** @var  $tutorLecturesFromDB */
                $tutorLecturesFromDB = TutorLectureQuery::get($id);
                if ($tutorLecturesFromDB) {
                    $tutorLectures = array();
                    foreach ($tutorLecturesFromDB as $tutorLectureFromDB) {
                        $tutorLecture = new TutorLectureModel($tutorLectureFromDB);
                        array_push($tutorLectures, $tutorLecture->get());
                    }
                    $profile->setTutorLectures($tutorLectures);
                }

                /** @var  $tutorSuitability */
                $tutorSuitability = new SuitabilityModel();

                $courseTypeFromDB = SuitabilityQuery::getCourseType($id);
                $courseType = new SuitableCourseTypeModel($courseTypeFromDB);
                $tutorSuitability->setCourseType($courseType->get());

                $facilityFromDB = SuitabilityQuery::getFacility($id);
                $facility = new SuitableFacilityModel($facilityFromDB);
                $tutorSuitability->setFacility($facility->get());

                $locationFromDB = SuitabilityQuery::getLocation($id);
                $location = new SuitableLocationModel($locationFromDB);
                $tutorSuitability->setLocation($location->get());

                $regionsFromDB = SuitabilityQuery::getRegions($id);
                $regions = array();
                foreach ($regionsFromDB as $regionFromDB) {
                    $region = new SuitableRegionModel($regionFromDB);
                    array_push($regions, $region->get());
                }
                $tutorSuitability->setRegions($regions);

                $profile->setTutorSuitability($tutorSuitability->get());
            }

            return $this->respondCreated('', $profile->get());
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
    }

    /**
     * @description Handle request to update profile info.
     * @param Request $request
     * @return mixed
     */
    public function updateProfile(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user[IDENTIFIER];
            $profile = ProfileQuery::update($userId, $request);
            if ($profile) {
                return $this->respondCreated(CHANGES_UPDATED_SUCCESSFULLY);
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Handle request to upload profile picture.
     * @param Request $request
     * @return mixed
     */
    public function uploadProfilePicture(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array (
                BASE64 => 'required',
                FILE_TYPE => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
            } else {
                $picture = Picture::upload($request[BASE64], $request[FILE_TYPE], $userId);
                $result = ProfileQuery::updatePicture($userId, $picture);
                if ($result) {
                    return $this->respondCreated(PICTURE_UPLOADED_SUCCESSFULLY, $picture);
                } else {
                    return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

}
