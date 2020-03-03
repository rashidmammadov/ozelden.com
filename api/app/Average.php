<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Average extends Model {

    protected $table = DB_AVERAGE_TABLE;
    protected $primaryKey = USER_ID;

    protected $fillable = [
        USER_ID, RANKING_AVG, EXPERIENCE_AVG, PRICE_AVG
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
