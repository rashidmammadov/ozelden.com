<?php

namespace App\Http\Controllers;

use App\Http\Models\OneSignalModel;
use App\Http\Queries\MySQL\OneSignalQuery;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class OneSignalController extends ApiController {

    /**
     * Handle request to create new student.
     * @param Request $request
     * @return mixed
     */
    public function set(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $rules = array (
                ONE_SIGNAL_DEVICE_ID => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
            } else {
                $oneSignalModel = new OneSignalModel($request);
                $oneSignalModel->setUserId($user[IDENTIFIER]);
                $oneSignalModel->setIp($request->getClientIp());
                $oneSignalQuery = OneSignalQuery::create($oneSignalModel->get());
                if ($oneSignalQuery) {
                    return $this->respondCreated(SUCCESSFULLY_SUBSCRIBED, '');
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
