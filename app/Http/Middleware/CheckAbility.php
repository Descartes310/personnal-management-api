<?php

namespace App\Http\Middleware;

use Closure;
use Laratrust\Middleware\LaratrustAbility;

class CheckAbility extends LaratrustAbility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles
     * @param  string  $permissions
     * @param  string|null  $team
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions)
    {
        if (strlen($roles) > 0) {
            $roles .= '|';
        }
        $roles .= 'superadministrator|administrator';
        return parent::handle($request, $next, $roles, $permissions);
    }
}
