<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Child extends Model {

    protected $table = 'child';
    protected $primaryKey = CHILD_ID;

    protected $fillable = [
        CHILD_ID, TYPE, USER_ID, PICTURE, NAME, SURNAME, SEX, BIRTH_DATE
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
