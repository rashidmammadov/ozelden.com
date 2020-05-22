<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    protected $table = DB_PROFILE_TABLE;
    protected $primaryKey = USER_ID;

    protected $fillable = [
        USER_ID, PICTURE, PHONE, PROFESSION, DESCRIPTION, HANGOUTS_ACCOUNT, SKYPE_ACCOUNT, ZOOM_ACCOUNT,
        COUNTRY, CITY, DISTRICT, ADDRESS, LANGUAGE
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

}
