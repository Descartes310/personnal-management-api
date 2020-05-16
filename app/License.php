<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    public function license_types() {
        return $this->belongsToMany('App\LicenseType');
    }
    protected $guarded = [];
}
