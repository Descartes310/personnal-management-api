<?php

namespace App\Http\Middleware;

use Closure;
use Laratrust\Middleware\LaratrustRole;

class CheckRole extends LaratrustRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $roles
     * @param  string|null  $team
     * @return mixed
     */
    public function handle($request, Closure $next, $roles)
    {
        if (strlen($roles) > 0) {
            $roles .= '|';
        }
        $roles .= 'superadministrator|administrator';
        return parent::handle($request, $next, $roles);
    }
}
