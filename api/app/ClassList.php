<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClassList extends Model {

    protected $table = 'class_list';
    protected $primaryKey = 'classId';

    protected $fillable = [
        'userId', 'className', 'tutorId', 'lectureArea', 'lectureTheme', 'day', 'content', 'status'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
