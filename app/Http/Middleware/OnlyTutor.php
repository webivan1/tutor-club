<?php

namespace App\Http\Middleware;

use App\Entity\Admin\Role;
use App\UseCases\Auth\AccessControlService;
use Illuminate\Http\Request;
use Closure;
use Bouncer;
use Cache;
use Auth;

class OnlyTutor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!(\Auth::user()->tutor && \Auth::user()->tutor->isActive())) {
            abort(403, t('It is only tutor'));
        }

        return $next($request);
    }
}
