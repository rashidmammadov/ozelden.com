<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model {

    protected $table = DB_LECTURE_TABLE;
    protected $primaryKey = LECTURE_ID;

    protected $fillable = [
        LECTURE_ID, LECTURE_AREA, LECTURE_THEME, AVERAGE_TRY
    ];

}
