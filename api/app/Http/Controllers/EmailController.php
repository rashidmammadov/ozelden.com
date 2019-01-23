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

    private function sendWelcomeEmail($user) {
        Mail::send('emails/welcome', $user, function ($message) {
            $message->from(NO_REPLY, 'özelden team');
            if (!empty($user[EMAIL])) {
                $message->to($user[EMAIL]);
            }
        });
    }
}
