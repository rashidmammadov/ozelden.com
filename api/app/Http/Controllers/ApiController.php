<?php

namespace App\Http\Controllers;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use PHPUnit\Util\Json;
use Response;
use \Illuminate\Http\Response as Res;
use Illuminate\Http\Request;

/**
 * Class ApiController
 * @package ozelden.com\api\Http\Auth\Controllers
 */
class ApiController extends Controller {
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->beforeFilter('auth', ['on' => 'post']);
    }

    /**
     * @var int
     */
    protected $statusCode = Res::HTTP_OK;

    /**
     * @return mixed
     */
    public function getStatusCode() {
        return $this->statusCode;
    }

    /**
     * @param $statusCode
     * @return ApiController response
     */
    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $message
     * @param $data
     * @return json respond
     */
    public function respondCreated($message, $data = null) {
        return $this->respond([
            'status' => 'success',
            'status_code' => Res::HTTP_CREATED,
            'message' => $message,
            'data' => $data
        ]);
    }

    /**
     * @param $message
     * @return json respond
     */
    public function respondNotFound($message = 'Not Found!'){
        return $this->respond([
            'status' => 'error',
            'status_code' => Res::HTTP_NOT_FOUND,
            'message' => $message,
        ]);
    }

    /**
     * @param $message
     * @param $errors
     * @return json respond
     */
    public function respondValidationError($message, $errors){
        return $this->respond([
            'status' => 'error',
            'status_code' => Res::HTTP_UNPROCESSABLE_ENTITY,
            'message' => $message,
            'data' => $errors
        ]);
    }

    /**
     * @param $message
     * @return json respond
     */
    public function respondWithError($message){
        return $this->respond([
            'status' => 'error',
            'status_code' => Res::HTTP_UNAUTHORIZED,
            'message' => $message,
        ]);
    }

    /**
     * @param $data
     * @param $headers
     * @return json Response
     */
    public function respond($data, $headers = []){
        return Response::json($data, $this->getStatusCode(), $headers);
    }
}
