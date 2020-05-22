<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function license_type() {
        return $this->belongsTo('App\LicenseType', 'license_type_id');
    }
}
