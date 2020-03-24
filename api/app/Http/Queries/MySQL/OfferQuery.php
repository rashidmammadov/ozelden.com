<?php

namespace App\Http\Queries\MySQL;

use App\Offer;
use Illuminate\Database\QueryException;

class OfferQuery extends Query {

    /**
     * get offers of given user.
     * @param $userId - holds the user id.
     * @param $userType - holds the user type.
     * @param $itemPerPage - holds the offers per request.
     * @return mixed
     */
    public static function getOffers($userId, $userType, $itemPerPage) {
        try {
            $query = Offer::where(SENDER_ID, EQUAL_SIGN, $userId)
                ->orWhere(RECEIVER_ID, EQUAL_SIGN, $userId)
                ->leftJoin(DB_TUTOR_LECTURE_TABLE, function ($join) use ($userId) {
                    $join->on(DB_TUTOR_LECTURE_TABLE.'.'.TUTOR_LECTURE_ID, EQUAL_SIGN, DB_OFFER_TABLE.'.'.TUTOR_LECTURE_ID);
                })
                ->leftJoin(DB_USERS_TABLE, function ($join) use ($userType, $userId) {
                    if ($userType == TUTORED) {
                        $join->on(DB_USERS_TABLE.'.'.IDENTIFIER, EQUAL_SIGN, DB_OFFER_TABLE.'.'.RECEIVER_ID);
                    } else {
                        $join->on(DB_USERS_TABLE.'.'.IDENTIFIER, EQUAL_SIGN, DB_OFFER_TABLE.'.'.SENDER_ID);
                    }
                })
                ->select('*', DB_OFFER_TABLE.'.'.UPDATED_AT)
                ->orderBy(DB_OFFER_TABLE.'.'.UPDATED_AT, 'desc')
                ->paginate($itemPerPage);
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
}
