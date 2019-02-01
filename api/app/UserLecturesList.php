<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLecturesList extends Model {

    protected $table = 'user_lectures_list';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        USER_ID, LECTURE_AREA, LECTURE_THEME, EXPERIENCE, PRICE
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
