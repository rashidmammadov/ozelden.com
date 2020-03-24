<?php

namespace App\Http\Controllers;

use App\Http\Models\OfferModel;
use App\Http\Models\StudentModel;
use App\Http\Models\TutorLectureModel;
use App\Http\Models\UserModel;
use App\Http\Queries\MySQL\OfferQuery;
use App\Http\Queries\MySQL\StudentQuery;
use App\Http\Utilities\CustomDate;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;

class OfferController extends ApiController {

    /**
     * Handle request to get user`s offers.
     * @return mixed
     */
    public function get() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            return $this->getOffers($user);
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

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
     * Get offers from DB of related user.
     * @param $user - holds the user.
     * @return mixed
     */
    private function getOffers($user) {
        $offersFromDB = OfferQuery::getOffers($user[IDENTIFIER], $user[TYPE], 20);
        if ($offersFromDB) {
            $offers = array();
            foreach ($offersFromDB as $offerFromDB) {
                $offer = new OfferModel($offerFromDB);

                if ($offer->getReceiverId() == $user[IDENTIFIER]) {
                    /** @var  $sender */
                    $sender = new UserModel();
                    $sender->setName($offerFromDB[NAME]);
                    $sender->setSurname($offerFromDB[SURNAME]);
                    $offer->setSender($sender->get());
                } else if ($offer->getSenderId() == $user[IDENTIFIER]) {
                    /** @var  $receiver */
                    $receiver = new UserModel();
                    $receiver->setName($offerFromDB[NAME]);
                    $receiver->setSurname($offerFromDB[SURNAME]);
                    $offer->setReceiver($receiver->get());
                }

                /** @var  $student */
                if ($offer->getStudentId()) {
                    $studentFromDB = StudentQuery::getStudentById($offer->getStudentId());
                    if ($studentFromDB) {
                        $student = new StudentModel($studentFromDB);
                        $offer->setStudent($student->get());
                    }
                }

                /** @var  $tutorLecture */
                $tutorLecture = new TutorLectureModel();
                $tutorLecture->setLectureArea($offerFromDB[LECTURE_AREA]);
                $tutorLecture->setLectureTheme($offerFromDB[LECTURE_THEME]);
                $offer->setTutorLecture($tutorLecture->get());

                if ($user[TYPE] !== $offerFromDB[SENDER_TYPE]) {
                    $offer->setOfferType(OFFER_IN);
                } else {
                    $offer->setOfferType(OFFER_OUT);
                }

                $offer->setUpdatedAt(CustomDate::dateToMillisecond($offerFromDB[UPDATED_AT]));

                array_push($offers, $offer->get());
            }
            return $this->respondWithPagination('', $offersFromDB, $offers);
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
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
        $offerResultFromDB = OfferQuery::create($offer->get());
        if ($offerResultFromDB) {
            $offerModel = new OfferModel($offerResultFromDB);
            return $this->respondCreated(OFFER_SENT_SUCCESSFULLY, $offerModel->get());
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
    }

}
