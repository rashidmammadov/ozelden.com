<?php

namespace App\Http\Utilities;

use App\Http\Queries\MySQL\OfferQuery;
use App\Http\Queries\MySQL\UserQuery;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Email {

    public static function send($type, $data) {
        if ($type == EMAIL_TYPE_PAID_SERVICE) {
            return self::sendEmailPaidService($data);
        }else if ($type == EMAIL_TYPE_OFFER) {
            return self::sendEmailOffer($data);
        } else if ($type == EMAIL_TYPE_OFFER_STATUS) {
            return self::sendEmailOfferStatus($data);
        } else if ($type == EMAIL_TYPE_RESET_PASSWORD) {
            return self::sendEmailResetPassword($data);
        } else if ($type == EMAIL_TYPE_WELCOME) {
            return self::sendEmailWelcome($data);
        }
    }

    private static function sendEmailPaidService($paidService) {
        $user = UserQuery::getUserById($paidService[USER_ID]);
        if ($user) {
            $data = array(
                EMAIL => $user[EMAIL],
                REFERENCE_CODE => $paidService[REFERENCE_CODE],
                ITEM => $paidService[ITEM],
                PRICE => $paidService[PRICE]
            );
            try {
                Log::info('Paid service email sending to ' . $data[EMAIL]);
                Mail::send('emails/paid-service', $data, function ($message) use ($data) {
                    $message->subject('💳 Başarılı Satın Alma');
                    $message->from(NO_REPLY, 'ozelden.com takımı');
                    $message->to($data[EMAIL]);
                });
                Log::info('Paid service email sent to ' . $data[EMAIL] . ' successfully');
                return true;
            } catch (\Exception $e) {
                Log::error('Paid service email to ' . $data[EMAIL] . ': ' . $e->getMessage());
            }
        }
    }

    private static function sendEmailOffer($offerId) {
        $offer = OfferQuery::getOffer($offerId);
        if ($offer) {
            $data = array(
                EMAIL => $offer[RECEIVER.'_'.EMAIL],
                NAME => $offer[SENDER.'_'.NAME],
                SURNAME => $offer[SENDER.'_'.SURNAME],
                LECTURE_AREA => $offer[LECTURE_AREA],
                LECTURE_THEME => $offer[LECTURE_THEME],
                OFFER => $offer[OFFER]
            );
            try {
                Log::info('Offer email sending to ' . $data[EMAIL]);
                Mail::send('emails/new-offer', $data, function ($message) use ($data) {
                    $message->subject('🔔 Yeni Teklif');
                    $message->from(NO_REPLY, 'ozelden.com takımı');
                    $message->to($data[EMAIL]);
                });
                Log::info('Offer email sent to ' . $data[EMAIL] . ' successfully');
                return true;
            } catch (\Exception $e) {
                Log::error('Offer email to ' . $data[EMAIL] . ': ' . $e->getMessage());
            }
        }
    }

    private static function sendEmailOfferStatus($offerId) {
        $offer = OfferQuery::getOffer($offerId);
        if ($offer) {
            $data = array(
                IDENTIFIER => $offer[RECEIVER.'_'.IDENTIFIER],
                EMAIL => $offer[SENDER.'_'.EMAIL],
                NAME => $offer[RECEIVER.'_'.NAME],
                SURNAME => $offer[RECEIVER.'_'.SURNAME],
                LECTURE_AREA => $offer[LECTURE_AREA],
                LECTURE_THEME => $offer[LECTURE_THEME],
                OFFER => $offer[OFFER],
                STATUS => $offer[STATUS]
            );
            try {
                Log::info('Offer status email sending to ' . $data[EMAIL]);
                Mail::send('emails/offer-status', $data, function ($message) use ($data) {
                    $message->subject('💬 Teklif Durumu');
                    $message->from(NO_REPLY, 'ozelden.com takımı');
                    $message->to($data[EMAIL]);
                });
                Log::info('Offer status email sent to ' . $data[EMAIL] . ' successfully');
                return true;
            } catch (\Exception $e) {
                Log::error('Offer status email to ' . $data[EMAIL] . ': ' . $e->getMessage());
            }
        }
    }

    private static function sendEmailResetPassword($data) {
        $user = array(
            EMAIL => $data[EMAIL],
            PASSWORD => $data[PASSWORD]
        );
        try {
            Log::info('Reset password email sending to ' . $user[EMAIL]);
            Mail::send('emails/reset-password', $user, function ($message) use ($user) {
                $message->subject('🔐 Şifre Yenileme');
                $message->from(NO_REPLY, 'ozelden.com takımı');
                $message->to($user[EMAIL]);
            });
            Log::info('Reset password email sent to ' . $user[EMAIL] . ' successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Reset password email to ' .  $user[EMAIL] . ': ' . $e->getMessage());
        }
    }

    private static function sendEmailWelcome($data) {
        $user = array(
            TYPE => $data[TYPE],
            NAME => $data[NAME],
            SURNAME => $data[SURNAME],
            EMAIL => $data[EMAIL],
            PASSWORD => $data[PASSWORD]
        );
        try {
            Log::info('Welcome email sending to ' . $user[EMAIL]);
            Mail::send('emails/welcome', $user, function ($message) use ($user) {
                $message->subject('🎉 Hoş Geldiniz!');
                $message->from(NO_REPLY, 'ozelden.com takımı');
                $message->to($user[EMAIL]);
            });
            Log::info('Welcome email sent to ' . $user[EMAIL] . ' successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Welcome email to ' .  $user[EMAIL] . ': ' . $e->getMessage());
        }
    }

}
