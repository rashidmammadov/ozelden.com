<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finance extends Model {

    protected $table = DB_FINANCE_TABLE;
    protected $primaryKey = FINANCE_ID;

    protected $fillable = [
        FINANCE_ID, USER_ID, REFERENCE_CODE, ITEM, PRICE, PRICE_WITH_COMMISSION
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
