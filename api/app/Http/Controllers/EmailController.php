<?php

namespace App\Http\Controllers;

use Mail;


class EmailController extends Controller {

    public function __construct() {
        define('NO_REPLY', 'no-reply@ozelden.com');
    }

    public function send($type, $data) {

        if ($type == WELCOME_EMAIL) {
            $this->sendWelcomeEmail($data);
        }
    }

    private function sendWelcomeEmail($data) {
        $user = array(
            TYPE => $data[TYPE],
            NAME => $data[NAME],
            SURNAME => $data[SURNAME],
            EMAIL => $data[EMAIL],
            PASSWORD => $data[PASSWORD]
        );
        Mail::send('emails/welcome', $user, function ($message) use ($user) {
            $message->subject('🎉 Hoş Geldin!');
            $message->from(NO_REPLY, 'özelden team');
            $message->to($user[EMAIL]);
        });
    }
}
