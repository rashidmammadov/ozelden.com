<?php

namespace App\Http\Controllers;

use App\Http\Models\OfferModel;
use App\Http\Models\PaidServiceModel;
use App\Http\Models\StudentModel;
use App\Http\Models\TutorLectureModel;
use App\Http\Models\UserModel;
use App\Http\Queries\MySQL\OfferQuery;
use App\Http\Queries\MySQL\PaidServiceQuery;
use App\Http\Queries\MySQL\StudentQuery;
use App\Http\Utilities\CustomDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
     * Handle request get user`s receiver offers with waiting status.
     * @return mixed
     */
    public function getReceivedOffersCount() {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $resultFromDB = OfferQuery::getWaitingReceivedOffersCount($user[IDENTIFIER]);
            return $this->respondCreated('', $resultFromDB);
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
                return $this->preparingPreOffer($user, $request);
            }
        } catch (JWTException $e) {
            $this->setStatusCode(401);
            $this->setMessage(AUTHENTICATION_ERROR);
            return $this->respondWithError($e->getMessage());
        }
    }

    /**
     * Handle request to update offer status.
     * @param $offerId - holds the offer id.
     * @param Request $request - holds the request.
     * @return mixed
     */
    public function updateStatus($offerId, Request $request) {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            $rules = array (
                STATUS => 'required'
            );
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->respondValidationError(FIELDS_VALIDATION_FAILED, $validator->errors());
            } else {
                return $this->preparingPreUpdateOfferStatus($offerId, $user, $request);
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
        $offersFromDB = OfferQuery::getOffers($user[IDENTIFIER], 10);
        if ($offersFromDB) {
            $offers = array();
            foreach ($offersFromDB as $offerFromDB) {
                $offer = new OfferModel($offerFromDB);

                /** @var  $sender */
                $sender = new UserModel();
                $sender->setIdentifier($offerFromDB[SENDER.'_'.IDENTIFIER]);
                $sender->setName($offerFromDB[SENDER.'_'.NAME]);
                $sender->setSurname($offerFromDB[SENDER.'_'.SURNAME]);
                $offer->setSender($sender->get());

                /** @var  $receiver */
                $receiver = new UserModel();
                $receiver->setIdentifier($offerFromDB[RECEIVER.'_'.IDENTIFIER]);
                $receiver->setName($offerFromDB[RECEIVER.'_'.NAME]);
                $receiver->setSurname($offerFromDB[RECEIVER.'_'.SURNAME]);
                $offer->setReceiver($receiver->get());

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

                $offer->setUpdatedAt(CustomDate::dateToMillisecond($offerFromDB[CREATED_AT]));

                array_push($offers, $offer->get());
            }
            return $this->respondWithPagination('', $offersFromDB, $offers);
        } else {
            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
        }
    }

    /**
     * Control offer`s availability before operation.
     * @param $sender - holds the sender data.
     * @param $request - holds the offer data.
     * @return mixed
     */
    private function preparingPreOffer($sender, $request) {
        if ($sender[TYPE] == TUTOR) {
            $paidServiceFromDB = PaidServiceQuery::get($sender[IDENTIFIER]);
            if ($paidServiceFromDB) {
                $paidServiceModel = new PaidServiceModel($paidServiceFromDB);
                $remainingBids = $paidServiceModel->getBid();
                if ($remainingBids > 0) {
                    $paidServiceModel->setBid($remainingBids - 1);
                    $updatePaidService = PaidServiceQuery::update($sender[IDENTIFIER], $paidServiceModel->get());
                    if ($updatePaidService) {
                        return $this->setOffer($sender, $request);
                    } else {
                        return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                    }
                } else {
                    return $this->respondWithError(NO_SUFFICIENT_BIDS);
                }
            } else {
                return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
            }
        } else if ($sender[TYPE] == TUTORED) {
            return $this->setOffer($sender, $request);
        }
    }

    /**
     * Control offer status`s availability before update operation.
     * @param $offerId - holds the offer id.
     * @param $user - holds the sender data.
     * @param $request - holds the updated offer status data.
     * @return mixed
     */
    private function preparingPreUpdateOfferStatus($offerId, $user, $request) {
        if ($user[TYPE] == TUTOR) {
            if ($request[STATUS] == OFFER_STATUS_APPROVED) {
                $paidServiceFromDB = PaidServiceQuery::get($user[IDENTIFIER]);
                if ($paidServiceFromDB) {
                    $paidServiceModel = new PaidServiceModel($paidServiceFromDB);
                    $remainingBids = $paidServiceModel->getBid();
                    if ($remainingBids > 0) {
                        $paidServiceModel->setBid($remainingBids - 1);
                        $updatePaidService = PaidServiceQuery::update($user[IDENTIFIER], $paidServiceModel->get());
                        if ($updatePaidService) {
                            return $this->updateOfferStatus($offerId, $user, $request);
                        } else {
                            return $this->respondWithError(SOMETHING_WRONG_WITH_DB);
                        }
                    } else {
                        return $this->respondWithError(NO_SUFFICIENT_BIDS);
                    }
                } else {
                    return $this->respondWithError(NO_SUFFICIENT_BIDS);
                }
            } else {
                return $this->updateOfferStatus($offerId, $user, $request);
            }
        } else if ($user[TYPE] == TUTORED) {
            return $this->updateOfferStatus($offerId, $user, $request);
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

    /**
     * Update offer status.
     * @param $offerId - holds the offer id.
     * @param $user - holds the sender data.
     * @param $request - holds the request.
     * @return mixed
     */
    private function updateOfferStatus($offerId, $user, $request) {
        $offerStatusResult = OfferQuery::updateStatus($offerId, $user[IDENTIFIER], $request[STATUS]);
        if ($offerStatusResult) {
            return $this->respondCreated(OFFER_STATUS_UPDATED_SUCCESSFULLY);
        } else {
            return $this->respondWithError(PERMISSION_DENIED);
        }
    }

}
