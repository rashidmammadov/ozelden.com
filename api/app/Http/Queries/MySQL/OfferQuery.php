<?php

namespace App\Http\Queries\MySQL;

use App\Offer;
use Illuminate\Database\QueryException;

class OfferQuery extends Query {

    /**
     * get offers of given user.
     * @param $userId - holds the user id.
     * @param $itemPerPage - holds the offers per request.
     * @return mixed
     */
    public static function getOffers($userId, $itemPerPage) {
        try {
            $query = Offer::where(SENDER_ID, EQUAL_SIGN, $userId)
                ->orWhere(RECEIVER_ID, EQUAL_SIGN, $userId)
                ->leftJoin(DB_TUTOR_LECTURE_TABLE, function ($join) use ($userId) {
                    $join->on(DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_LECTURE_ID, EQUAL_SIGN, DB_OFFER_TABLE.'.'.TUTOR_LECTURE_ID);
                })
                ->leftJoin(DB_USERS_TABLE.' as '.SENDER, function ($join) use ($userId) {
                    $join->on(SENDER.'.'.IDENTIFIER, EQUAL_SIGN, DB_OFFER_TABLE.'.'.SENDER_ID);
                })
                ->leftJoin(DB_USERS_TABLE.' as '.RECEIVER, function ($join) use ($userId) {
                    $join->on(RECEIVER.'.'.IDENTIFIER, EQUAL_SIGN, DB_OFFER_TABLE.'.'.RECEIVER_ID);
                })
                ->select('*', DB_OFFER_TABLE.'.'.UPDATED_AT, DB_OFFER_TABLE.'.'.CREATED_AT,
                    SENDER.'.'.IDENTIFIER.' as '.SENDER.'_'.IDENTIFIER,
                    SENDER.'.'.NAME.' as '.SENDER.'_'.NAME,
                    SENDER.'.'.SURNAME.' as '.SENDER.'_'.SURNAME,
                    RECEIVER.'.'.IDENTIFIER.' as '.RECEIVER.'_'.IDENTIFIER,
                    RECEIVER.'.'.NAME.' as '.RECEIVER.'_'.NAME,
                    RECEIVER.'.'.SURNAME.' as '.RECEIVER.'_'.SURNAME
                )
                ->orderBy(DB_OFFER_TABLE.'.'.CREATED_AT, 'desc')
                ->paginate($itemPerPage);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Get waiting offers of given user.
     * @param $userId - holds the user id.
     * @return mixed
     */
    public static function getWaitingReceivedOffersCount($userId) {
        try {
            $query = Offer::where(RECEIVER_ID, EQUAL_SIGN, $userId)
                ->where(STATUS, EQUAL_SIGN, OFFER_STATUS_WAITING)
                ->count();
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Save sent offer on DB.
     * @param $offer - holds the offer detail.
     * @return mixed
     */
    public static function create($offer) {
        try {
            $query = Offer::create([
                OFFER_ID => $offer[OFFER_ID],
                SENDER_ID => $offer[SENDER_ID],
                RECEIVER_ID => $offer[RECEIVER_ID],
                STUDENT_ID => !empty($offer[STUDENT_ID]) ? $offer[STUDENT_ID] : null,
                SENDER_TYPE => $offer[SENDER_TYPE],
                TUTOR_LECTURE_ID => $offer[TUTOR_LECTURE_ID],
                OFFER => $offer[OFFER],
                CURRENCY => !empty($offer[CURRENCY]) ? $offer[CURRENCY] : TURKISH_LIRA,
                STATUS => !empty($offer[STATUS]) ? $offer[STATUS] : OFFER_STATUS_WAITING,
            ]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }

    /**
     * Update status of given offer.
     * @param $offerId - holds the offer id.
     * @param $userId - holds the receiver id.
     * @param $status - holds the new status of offer.
     * @return mixed
     */
    public static function updateStatus($offerId, $userId, $status) {
        try {
            $query = Offer::where(OFFER_ID, EQUAL_SIGN, $offerId)
                ->where(RECEIVER_ID, EQUAL_SIGN, $userId)
                ->where(STATUS, EQUAL_SIGN, OFFER_STATUS_WAITING)
                ->update([STATUS => $status]);
            return $query;
        } catch (QueryException $e) {
            self::logException($e, debug_backtrace());
        }
    }
}
