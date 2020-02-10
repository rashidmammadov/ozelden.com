<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        IDENTIFIER, TYPE, NAME, SURNAME, BIRTHDAY, EMAIL, PASSWORD, SEX, STATE, ONESIGNAL_DEVICE_ID
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        PASSWORD, REMEMBER_TOKEN
    ];
}
