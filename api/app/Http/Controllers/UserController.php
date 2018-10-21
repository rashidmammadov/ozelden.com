<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\SuitabilityScheduleController as SuitabilitySchedule;
use JWTAuth;
use Response;
use App\Repository\Transformers\UserTransformer;
use \Illuminate\Http\Response as Res;
use Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends ApiController
{
    /**
     * @var \App\Repository\Transformers\UserTransformer
     * */
    protected $userTransformer;

    /**
     * UserController constructor.
     * @param UserTransformer $userTransformer
     */
    public function __construct(userTransformer $userTransformer) {
        $this->userTransformer = $userTransformer;
    }

    /**
     * @description: Get own info by token
     * @param Request $request
     * @return json: User info
     */
    public function myUser(Request $request) {
        try {
            $user = JWTAuth::toUser($request['remember_token']);
            return $this->respondCreated("USER_INFO", $this->userTransformer->transform($user));
        } catch (JWTException $e) {
            return $this->respondValidationError("INTERNAL_ERROR", $e);
        }
    }

    /**
     * @description: Api user auth method
     * @param Request $request
     * @return json : Json String response
     */
    public function auth(Request $request) {
        $rules = array (
            'email' => 'required|email',
            'password' => 'required',
        );

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()){
            return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
        } else {
            $user = User::where('email', $request['email'])->first();

            if ($user) {
                $remember_token = $user->remember_token;
                if ($remember_token == NULL){
                    return $this->_login($request['email'], $request['password'], false);
                }

                try {
                    $user = JWTAuth::toUser($remember_token);
                    return $this->respondCreated("USER_LOGGED_IN_SUCCESSFULLY", $this->userTransformer->transform($user));
                } catch (JWTException $e) {
                    $user->remember_token = NULL;
                    $user->save();
                    return $this->respondValidationError("INTERNAL_ERROR", $e);
                }
            } else {
                return $this->respondWithError("INVALID_EMAIL_OR_PASSWORD");
            }
        }
    }

    /**
     * @description: Api user register method
     * @param Request $request
     * @return json : Json String response
     */
    public function register(Request $request) {
        $rules = array (
            'type' => 'required|max:255',
            'name' => 'required|max:255',
            'surname' => 'required|max:255',
            'birthDate' => 'required',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'sex' => 'required|max:9'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator-> fails()){
            return $this->respondValidationError("FIELDS_VALIDATION_FAILED", $validator->errors());
        } else {
             User::create([
                'type' => $request['type'],
                'name' => $request['name'],
                'surname' => $request['surname'],
                'birthDate' => $request['birthDate'],
                'email' => $request['email'],
                'password' => \Hash::make($request['password']),
                'sex' => $request['sex']
            ]);

            return $this->_login($request['email'], $request['password'], true);
        }
    }

    /**
     * @description: Api user logout method
     * @param Request $request
     * @return json : Json String response
     */
    public function logout(Request $request) {
        try {
            $user = JWTAuth::toUser($request['remember_token']);
            $user->remember_token = NULL;
            $user->save();
            JWTAuth::setToken($request['remember_token'])->invalidate();
            $this->setStatusCode(Res::HTTP_OK);
            return $this->respondCreated("LOGGED_OUT_SUCCESSFULLY", null);
        } catch(JWTException $e) {
            return $this->respondValidationError("INTERNAL_ERROR", $e);
        }
    }

    /**
     * @description: Api user login method
     * @param $email
     * @param $password
     * @param $newUser
     * @return json : Json String response
     */
    private function _login($email, $password, $newUser) {
        $credentials = ['email' => $email, 'password' => $password];
        if ( ! $token = JWTAuth::attempt($credentials)) {
            return $this->respondWithError("USER_DOES_NOT_EXIST");
        }

        $user = JWTAuth::toUser($token);

        $user->remember_token = $token;
        $user->save();

        if ($newUser) {
            $suitabilitySchedule = new SuitabilitySchedule;
            $suitabilitySchedule->create($user->id);
        }

        return $this->respondCreated("USER_LOGGED_IN_SUCCESSFULLY", $this->userTransformer->transform($user));
    }
}
