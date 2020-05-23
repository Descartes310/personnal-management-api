<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    public function license_types() {
        return $this->belongsToMany('App\LicenseType');
    }
    protected $guarded = [];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function license_type() {
        return $this->belongsTo('App\LicenseType', 'license_type_id');
    }
}
