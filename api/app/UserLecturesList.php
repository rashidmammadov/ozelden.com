<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLecturesList extends Model {

    protected $table = 'user_lectures_list';
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'userId', 'lectureArea', 'lectureTheme', 'experience', 'price'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
