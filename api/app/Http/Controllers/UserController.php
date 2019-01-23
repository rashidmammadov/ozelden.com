<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\SuitabilityScheduleController;
use JWTAuth;
use Response;
use App\Repository\Transformers\UserTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\EmailController;

class UserController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\UserTransformer
     * */
    protected $userTransformer;
    protected $suitabilitySchedule;
    protected $email;

    /**
     * UserController constructor.
     * @param UserTransformer $userTransformer
     * @param SuitabilityScheduleController $suitabilityScheduleController
     * @param \App\Http\Controllers\EmailController $emailController
     */
    public function __construct(userTransformer $userTransformer,
                                suitabilityScheduleController $suitabilityScheduleController,
                                emailController $emailController) {
        $this->userTransformer = $userTransformer;
        $this->suitabilitySchedule = $suitabilityScheduleController;
        $this->email = $emailController;
    }

    /**
     * @description: Get own info by token
     * @return mixed: User info
     */
    public function refreshUser() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $this->respondCreated("Get User", $this->userTransformer->transform($user));
        } catch (TokenExpiredException $e){
            $refreshedToken = JWTAuth::refresh(JWTAuth::getToken());
            $user = JWTAuth::setToken($refreshedToken)->toUser();
            $user->remember_token = $refreshedToken;
            $user->save();
            return $this->respondCreated("Token Refreshed", $this->userTransformer->transform($user));
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description: Api user auth method
     * @param Request $request
     * @return mixed : Json String response
     */
    public function auth(Request $request) {
        $rules = array (
            EMAIL => 'required|email',
            PASSWORD => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
        } else {
            $user = User::where(EMAIL, $request[EMAIL])->first();

            if ($user) {
                $remember_token = $user->remember_token;
                if ($remember_token == NULL){
                    return $this->_login($request[EMAIL], $request[PASSWORD], false);
                }

                try {
                    $user = JWTAuth::toUser($remember_token);
                    return $this->respondCreated("USER_LOGGED_IN_SUCCESSFULLY", $this->userTransformer->transform($user));
                } catch (JWTException $e) {
                    $user->remember_token = NULL;
                    $user->save();
                    $this->setStatusCode($e->getStatusCode());
                    return $this->respondWithError($e->getMessage());
                }
            } else {
                return $this->respondWithError("INVALID_EMAIL_OR_PASSWORD");
            }
        }
    }

    /**
     * @description: Api user register method
     * @param Request $request
     * @return mixed : Json String response
     */
    public function register(Request $request) {
        $rules = array (
            TYPE => 'required|max:255',
            NAME => 'required|max:255',
            SURNAME => 'required|max:255',
            BIRTH_DATE => 'required',
            EMAIL => 'required|email|max:255|unique:users',
            PASSWORD => 'required|min:6|confirmed',
            PASSWORD_CONFIRMATION => 'required|min:6',
            SEX => 'required|max:9'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
        } else {
            $params = array(
                TYPE => $request[TYPE],
                NAME => $request[NAME],
                SURNAME => $request[SURNAME],
                BIRTH_DATE => $request[BIRTH_DATE],
                EMAIL => $request[EMAIL],
                PASSWORD => \Hash::make($request[PASSWORD]),
                SEX => $request[SEX]
            );
            User::create($params);

            $this->email->send(WELCOME_EMAIL, $params);
            return $this->_login($request[EMAIL], $request[PASSWORD], true);
        }
    }

    /**
     * @description: Api user logout method
     * @return mixed : Json String response
     */
    public function logout() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $token = JWTAuth::getToken();
            $user->remember_token = NULL;
            $user->save();
            JWTAuth::setToken($token)->invalidate();
            $this->setStatusCode(Res::HTTP_OK);
            return $this->respondCreated("LOGGED_OUT_SUCCESSFULLY", null);
        } catch(JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description: Api user login method
     * @param $email
     * @param $password
     * @param $newUser
     * @return mixed : Json String response
     */
    private function _login($email, $password, $newUser) {
        $credentials = [EMAIL => $email, PASSWORD => $password];
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return $this->respondWithError("USER_DOES_NOT_EXIST");
        }

        $user = JWTAuth::toUser($token);
        $user->remember_token = $token;
        $user->save();

        if ($newUser) {
            $this->suitabilitySchedule->create($user->id);
        }

        return $this->respondCreated("USER_LOGGED_IN_SUCCESSFULLY", $this->userTransformer->transform($user));
    }
}
