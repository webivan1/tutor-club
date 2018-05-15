<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthDev
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$this->access($request)) {
            return response('Access denied', 401, [
                'WWW-Authenticate' => 'Basic realm=Restricted area'
            ]);
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function access(Request $request)
    {
        if (app()->environment() === 'local' && !empty(env('APP_USER'))) {
            $user = $request->server('PHP_AUTH_USER');
            $passwd = $request->server('PHP_AUTH_PW');

            return $user == env('APP_USER') && $passwd == env('APP_PASSWORD');
        }

        return true;
    }
}
