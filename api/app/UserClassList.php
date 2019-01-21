<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserClassList extends Model {

    protected $table = 'user_class_list';
    protected $primaryKey = 'classId';

    protected $fillable = [
        'userId', 'className', 'tutorId', 'lectureArea', 'lectureTheme', 'city', 'district', 'day', 'content', 'status'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
