<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;


class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        TYPE, NAME, SURNAME, BIRTH_DATE, EMAIL, PASSWORD, SEX, STATE
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        PASSWORD, REMEMBER_TOKEN,
    ];

    public function child() {
        return $this->hasOne('App\Child');
    }

    public function class_list() {
        return $this->hasMany('App\UserClassList');
    }

    public function profile() {
        return $this->hasOne('App\Profile');
    }

    public function suitability_schedule() {
        return $this->hasOne('App\SuitabilitySchedule');
    }

    public function user_lectures_list() {
        return $this->hasMany('App\UserLecturesList');
    }
}
