<?php

namespace App\Http\Controllers;

use App\Http\Models\PaidServiceModel;
use App\Http\Models\ProfileModel;
use App\Http\Models\UserModel;
use App\Http\Queries\MySQL\PaidServiceQuery;
use App\Http\Queries\MySQL\ProfileQuery;
use App\Http\Utilities\Iyzico;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaidServiceController extends ApiController {

    public function depositConfirmation(Request $request) {
        $iyzico = new Iyzico();
        return $iyzico->confirmPayment($request);
    }

    /**
     * Handle request to start 3DS deposit operation.
     * @param Request $request
     * @return mixed
     */
    public function deposit(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $this->startThreeDSTransaction($user, $request);
        } catch(JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

    /**
     * Handle request to get paid service of user.
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $this->getPaidService($user[IDENTIFIER]);
        } catch(JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

    /**
     * Get user`s paid service from DB.
     * @param $userId - holds the user id.
     * @return mixed
     */
    private function getPaidService($userId) {
        $paidServiceFromDB = PaidServiceQuery::get($userId);
        $paidService = new PaidServiceModel($paidServiceFromDB);
        return $this->respondCreated('', $paidService->get());
    }

    /**
     * Send request to iyzico to handle 3DS payment operation.
     * @param $user - holds the user data.
     * @param $request - holds the data from request.
     * @return mixed
     */
    private function startThreeDSTransaction($user, $request) {
        $userModel = new UserModel($user);
        $profileFromDB = ProfileQuery::getProfileById($userModel->getIdentifier());
        if ($profileFromDB) {
            $profileModel = new ProfileModel($profileFromDB);
            $ipAddress = $request->getClientIp();
            $iyzico = new Iyzico();
            $threeDS = $iyzico->startThreeDSInitialize($userModel, $profileModel, $request, $ipAddress);
            if ($threeDS) {
                return $this->respondCreated('3D Secure Confirm Page', $threeDS);
            } else {
                return $this->respondWithError(PAYMENT_VALIDATION_ERROR);
            }
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
    }

}
