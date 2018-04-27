<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class AccessAbility
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $ability
     * @return mixed
     */
    public function handle($request, Closure $next, $ability)
    {
        $user = Auth::user();

        if (!$user->isActive()) {
            return redirect()->route('logout')
                ->with('error', 'Ваш пользователь не активен!');
        }

        if (!$user->hasAbility(...explode('|', $ability))) {
            abort(403, 'У вас нет прав для этого раздела!');
        }

        return $next($request);
    }
}
