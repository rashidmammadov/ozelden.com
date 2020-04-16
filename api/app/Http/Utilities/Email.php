<?php

namespace App\Http\Utilities;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class Email {

    public static function send($type, $data) {
        if ($type == EMAIL_TYPE_RESET_PASSWORD) {
            return self::sendEmailResetPassword($data);
        } else if ($type == EMAIL_TYPE_WELCOME) {
            return self::sendEmailWelcome($data);
        }
    }

    private static function sendEmailResetPassword($data) {
        $user = array(
            EMAIL => $data[EMAIL],
            PASSWORD => $data[PASSWORD]
        );
        try {
            Log::info('Welcome email sending to ' . $user[EMAIL]);
            Mail::send('emails/reset-password', $user, function ($message) use ($user) {
                $message->subject('🔐 Şifre Yenileme');
                $message->from(NO_REPLY, 'ozelden.com takımı');
                $message->to($user[EMAIL]);
            });
            Log::info('Welcome email sent to ' . $user[EMAIL] . ' successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Welcome email to ' .  $user[EMAIL] . ': ' . $e->getMessage());
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
            Log::info('Reset password email sending to ' . $user[EMAIL]);
            Mail::send('emails/welcome', $user, function ($message) use ($user) {
                $message->subject('🎉 Hoş Geldiniz!');
                $message->from(NO_REPLY, 'ozelden.com takımı');
                $message->to($user[EMAIL]);
            });
            Log::info('Reset password email sent to ' . $user[EMAIL] . ' successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Reset password email to ' .  $user[EMAIL] . ': ' . $e->getMessage());
        }
    }

}
