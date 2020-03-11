<?php

namespace App\Http\Controllers;

use App\Http\Models\OfferModel;
use App\Http\Queries\MySQL\OfferQuery;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class OfferController extends ApiController {

    /**
     * Handle request to send offer.
     * @param Request $request
     * @return mixed
     */
    public function set(Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $rules = array (
                RECEIVER_ID => 'required',
                TUTOR_LECTURE_ID => 'required',
                OFFER => 'required'
            );

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
            } else {
                return $this->setOffer($user, $request);
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Send offer with given parameters.
     * @param $sender - holds the sender data.
     * @param $request - holds the request.
     * @return mixed
     */
    private function setOffer($sender, $request) {
        $offer = new OfferModel($request);
        $offer->setSenderId($sender[IDENTIFIER]);
        $offer->setSenderType($sender[TYPE]);
        $offerResultFromDB = OfferQuery::save($offer->get());
        if ($offerResultFromDB) {
            $offerModel = new OfferModel($offerResultFromDB);
            return $this->respondCreated(OFFER_SENT_SUCCESSFULLY, $offerModel->get());
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
    }

}
