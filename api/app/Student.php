<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Student extends Model {

    protected $table = DB_STUDENT_TABLE;
    protected $primaryKey = STUDENT_ID;

    protected $fillable = [
        STUDENT_ID, TYPE, PARENT_ID, PICTURE, NAME, SURNAME, BIRTHDAY, SEX
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
