<?php

namespace App\Http\Queries\MySQL;

use App\Offer;
use Illuminate\Database\QueryException;

class OfferQuery extends Query {

    /**
     * Save sent offer on DB.
     * @param $offer - holds the offer detail.
     * @return mixed
     */
    public static function save($offer) {
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
