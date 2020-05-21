<?php

namespace App\Http\Middleware;

use App\APIError;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $unauthorized = new APIError;
            $unauthorized->setStatus("401");
            $unauthorized->setCode("Unauthorized");
            $unauthorized->setMessage("Unauthorized");
            return response()->json($unauthorized, 401);
        }
    }
}
