<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaidService extends Model {

    protected $table = DB_PAID_SERVICE_TABLE;
    protected $primaryKey = PAID_SERVICE_ID;

    protected $fillable = [
        PAID_SERVICE_ID, TUTOR_ID, BID, BOOST, RECOMMEND
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

}
