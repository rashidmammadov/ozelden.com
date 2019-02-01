<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserClassList extends Model {

    protected $table = 'user_class_list';
    protected $primaryKey = CLASS_ID;

    protected $fillable = [
        USER_ID, CLASS_NAME, TUTOR_ID, LECTURE_AREA, LECTURE_THEME, CITY, DISTRICT, DAY, CONTENT, STATUS
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
