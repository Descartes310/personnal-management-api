<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vacation extends Model
{
    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class);
    }


    public function vacationType() {
        return $this->belongsTo(VacationType::class);
    }
}
