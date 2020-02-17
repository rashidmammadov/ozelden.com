<?php

namespace App\Http\Controllers;

use App\Http\Models\UserModel;
use App\Http\Queries\MySQL\ProfileQuery;
use App\Http\Queries\MySQL\UserQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Response as Res;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;

class UserController extends ApiController {

    /**
     * Authorized user to login.
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request) {
        $rules = array (
            EMAIL => 'required|email',
            PASSWORD => 'required',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            $user = UserQuery::getUserByEmail($request[EMAIL]);
            if ($user) {
                /** add one signal device id **/
                if ($request[ONESIGNAL_DEVICE_ID]) {
                    $user[ONESIGNAL_DEVICE_ID] = $request[ONESIGNAL_DEVICE_ID];
                    $user->save();
                }
                $remember_token = $user[REMEMBER_TOKEN];
                if ($remember_token == NULL) {
                    return $this->sign($request, false);
                }
                try {
                    JWTAuth::setToken($remember_token);
                    $user = JWTAuth::toUser();
                    $userModel = new UserModel($user);
                    return $this->respondCreated(LOGGED_IN_SUCCESSFULLY, $userModel->get());
                } catch (JWTException $e) {
                    $user->remember_token = NULL;
                    $user->save();
                    $this->setStatusCode(401);
                    $this->setMessage(AUTHENTICATION_ERROR);
                    return $this->respondWithError($this->getMessage());
                }
            } else {
                return $this->respondWithError(INVALID_EMAIL_OR_PASSWORD);
            }
        }
    }

    /**
     * Logout user and clear token.
     * @return mixed
     */
    public function logout() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $user->remember_token = NULL;
            $user->save();
            $this->setStatusCode(Res::HTTP_OK);
            return $this->respondCreated(LOGGED_OUT_SUCCESSFULLY);
        } catch(JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

    /**
     * Refresh user by token,
     *      or refresh token of user if expired.
     *      or throw exception message.
     * @return mixed: User info
     */
    public function refresh() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userModel = new UserModel($user);
            return $this->respondCreated("Get User", $userModel->get());
        } catch (TokenExpiredException $e) {
            $refreshedToken = JWTAuth::refresh(JWTAuth::getToken());
            $user = JWTAuth::setToken($refreshedToken)->toUser();
            $userModel = new UserModel($user);
            if ($userModel->getIdentifier()) {
                $user->remember_token = $refreshedToken;
                $user->save();
            }
            return $this->respondCreated("Token Refreshed", $userModel->get());
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($this->getMessage());
        }
    }

    /**
     * Handle request to create new user.
     *      returns users data if successful or returns exception message
     * @param Request $request
     * @return UserModel | String
     */
    public function register(Request $request) {
        $rules = array (
            NAME => 'required|max:100',
            SURNAME => 'required|max:100',
            EMAIL => 'required|email|max:100|unique:users',
            PHONE => 'required|max:10',
            TYPE => 'required|max:10',
            PASSWORD => 'required|min:6|confirmed',
            PASSWORD_CONFIRMATION => 'required|min:6',
            IDENTITY_NUMBER => 'required|max:11',
            SEX => 'required|max:10',
            BIRTHDAY => 'required',
            CITY => 'required',
            DISTRICT => 'required',
            ADDRESS => 'required'
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
        } else {
            $checkEmail = UserQuery::checkEmail($request[EMAIL]);
            if ($checkEmail) {
                return $this->respondWithError(THIS_EMAIL_ALREADY_EXIST);
            } else {
                if ($request[PASSWORD] != $request[PASSWORD_CONFIRMATION]) {
                    return $this->respondWithError(PASSWORD_VALIDATION_FAILED);
                } else {
                    $saveUserQueryResult = UserQuery::save($request);
                    if ($saveUserQueryResult) {
                        ProfileQuery::saveDefault($saveUserQueryResult[IDENTIFIER], $request);
                        return $this->sign($request, true);
                    } else {
                        return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                    }
                }
            }
        }
    }

    /**
     * Sign user if exist and send email if user registered.
     * @param Request $request
     * @param boolean $newUser
     * @return mixed
     */
    private function sign($request, $newUser) {
        $credentials = [EMAIL => $request[EMAIL], PASSWORD => $request[PASSWORD]];
        if (!$token = JWTAuth::attempt($credentials)) {
            return $this->respondWithError(USER_DOES_NOT_EXIST);
        }
        JWTAuth::setToken($token);
        $user = JWTAuth::toUser();
        $user->remember_token = $token;
        $user->save();
        if ($newUser) {
            // TODO:
            // Email::send(WELCOME_EMAIL, $request);
        }
        $userModel = new UserModel($user);
        return $this->respondCreated(LOGGED_IN_SUCCESSFULLY, $userModel->get());
    }
}
