<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        IDENTIFIER, TYPE, NAME, SURNAME, BIRTHDAY, EMAIL, IDENTITY_NUMBER, PASSWORD, SEX, STATE, ONE_SIGNAL_DEVICE_ID
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        PASSWORD, REMEMBER_TOKEN
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    public function average() {
        return $this->hasOne('App\Average');
    }

    public function announcement() {
        return $this->hasMany('App\Announcement');
    }

    public function finance() {
        return $this->hasMany('App\Finance');
    }

    public function paidService() {
        return $this->hasOne('App\PaidService');
    }

    public function profile() {
        return $this->hasOne('App\Profile');
    }

    public function suitableCourseType() {
        return $this->hasOne('App\SuitableCourseType');
    }

    public function suitableFacility() {
        return $this->hasOne('App\SuitableFacility');
    }

    public function suitableLocation() {
        return $this->hasOne('App\SuitableLocation');
    }

    public function suitableRegion() {
        return $this->hasMany('App\SuitableRegion');
    }

    public function student() {
        return $this->hasMany('App\Student');
    }

    public function tutorLecture() {
        return $this->hasMany('App\TutorLecture');
    }

    public function offer() {
        return $this->hasMany('App\Offer');
    }

    public function oneSignal() {
        return $this->hasMany('App\OneSignal');
    }
}
