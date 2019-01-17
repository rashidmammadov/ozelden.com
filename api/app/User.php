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
        'type', 'name', 'surname', 'birthDate', 'email', 'password', 'sex', 'state'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function suitability_schedule() {
        return $this->hasOne('App\SuitabilitySchedule');
    }

    public function user_lectures_list() {
        return $this->hasMany('App\UserLecturesList');
    }

    public function class_list() {
        return $this->hasMany('App\ClassList');
    }
}
