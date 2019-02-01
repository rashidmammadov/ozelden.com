<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    protected $table = 'profile';
    protected $primaryKey = USER_ID;

    protected $fillable = [
        USER_ID, PHONE, COUNTRY, CITY, DISTRICT, ADDRESS, LANGUAGE
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
