<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SuitableFacility extends Model {

    protected $table = DB_SUITABLE_FACILITY_TABLE;
    protected $primaryKey = TUTOR_ID;
    public $incrementing = false;

    protected $fillable = [
        TUTOR_ID, DEMO, GROUP_DISCOUNT, PACKAGE_DISCOUNT
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
