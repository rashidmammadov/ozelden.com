<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OneSignal extends Model {

    protected $table = DB_ONE_SIGNAL_TABLE;
    protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        USER_ID, ONE_SIGNAL_DEVICE_ID, DEVICE_TYPE, IP, STATUS
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
