<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {

    protected $table = DB_STUDENT_TABLE;
    protected $primaryKey = STUDENT_ID;

    protected $fillable = [
        STUDENT_ID, TYPE, PARENT_ID, PICTURE, NAME, SURNAME, BIRTHDAY, SEX
    ];

    public function announcement() {
        return $this->belongsToMany('App\Announcement');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function offer() {
        return $this->belongsToMany('App\Offer');
    }
}
