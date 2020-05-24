<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisciplinaryBoard extends Model
{
    protected $guarded = [];

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function disciplinaryteam(){
        return $this->belongsTo('App\DisciplinaryTeam', 'disciplinary_team_id');
    }
}
