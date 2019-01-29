<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model {

    protected $table = 'profile';
    protected $primaryKey = 'userId';

    protected $fillable = [
        'userId', 'phone', 'country', 'city', 'district', 'address', 'language'
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }
}
