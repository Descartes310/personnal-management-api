<?php

namespace App;

use Laratrust\Models\LaratrustRole;

class Role extends LaratrustRole
{
    protected $guarded = [];
    protected $primaryKey = 'id';

    public function permissions() {
        return $this->belongsToMany('App\Permission');
    }

    // public function permission_roles() {
    //     return $this->belongsToMany('App\PermissionRole');
    // }
}
