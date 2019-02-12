<?php

namespace App\Http\Controllers;

use App\Http\Queries\MySQL\ApiQuery;
use App\Library\Image;
use App\Repository\Transformers\ChildTransformer;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class ChildController extends ApiController {

    private $childTransformer;

    public function __construct(ChildTransformer $childTransformer) {
        $this->childTransformer = $childTransformer;
    }

    /**
     * @description handle request to set child of given parent.
     * @param Request $request
     * @return mixed
     */
    public function addNewChild(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $userType = $user->type;

            $rules = array(
                NAME => 'required|max:50',
                SURNAME => 'required|max:99',
                SEX => 'required',
                BIRTH_DATE => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $picture = null;
                if ($request[PICTURE]) {
                    $picture = Image::uploadProfilePicture($userId, CHILD, $request[PICTURE][BASE64], $request[PICTURE][FILE_TYPE]);
                }
                $params = array(
                    PICTURE => $picture,
                    NAME => $request[NAME],
                    SURNAME => $request[SURNAME],
                    SEX => $request[SEX],
                    BIRTH_DATE => $request[BIRTH_DATE]
                );

                if ($userType == STUDENT) {
                    ApiQuery::setChild($userId, $params);
                    return $this->respondCreated('CHILD_ADDED_SUCCESSFULLY');
                } else {
                    return $this->respondValidationError('UNAVAILABLE_USER_TYPE', $userType);
                }
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description handle request to get user`s children.
     * @return mixed
     */
    public function getUserChildren() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;

            $children = ApiQuery::getUserChildren($userId);
            $result = array();
            foreach ($children as $child) {
                array_push($result, $this->childTransformer->transform($child));
            }
            return $this->respondCreated('', $result);
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * @description handle request to delete user`s children.
     * @param Request $request
     * @return mixed
     */
    public function removeChild(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $rules = array(
                CHILD_ID => 'required',
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError('FIELDS_VALIDATION_FAILED', $validator->errors());
            } else {
                $childId = $request[CHILD_ID];
                ApiQuery::deleteChild($userId, $childId);
                return $this->respondCreated('CHILD_REMOVED_SUCCESSFULLY');
            }
        } catch (JWTException $e) {
            $this->setStatusCode($e->getStatusCode());
            return $this->respondWithError($e->getMessage());
        }
    }

}
