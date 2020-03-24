<?php

namespace App\Http\Controllers;

use App\Http\Models\AnnouncementModel;
use App\Http\Queries\MySQL\AnnouncementQuery;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class AnnouncementController extends ApiController {

    /**
     * Handle request to create announcement.
     * @param Request $request
     * @return mixed
     */
    public function set(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $rules = array (
                LECTURE_AREA => 'required',
                CITY => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
            } else {
                if ($user[TYPE] == TUTORED) {
                    return $this->createAnnouncement($user, $request);
                } else {
                    return $this->respondWithError(PERMISSION_DENIED);
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Create announcement with given parameters to save on DB.
     * @param $user - holds the user data.
     * @param $request - holds the announcement detail.
     * @return mixed
     */
    private function createAnnouncement($user, $request) {
        $tutoredId = $user[IDENTIFIER];
        $alreadyExists = AnnouncementQuery::checkExistsWithParams($tutoredId, $request);
        if (!$alreadyExists) {
            $announcementFromDb = AnnouncementQuery::create($tutoredId, $request);
            if ($announcementFromDb) {
                $announcement = new AnnouncementModel($announcementFromDb);
                return $this->respondCreated(ANNOUNCEMENT_CREATED_SUCCESSFULLY, $announcement->get());
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } else {
            return $this->respondWithError(ANNOUNCEMENT_ALREADY_EXIST_WITH_SAME_PARAMETERS);
        }

    }

}
