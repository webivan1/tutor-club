<?php

namespace App\Http\Middleware;

use App\Entity\Admin\Role;
use App\UseCases\Auth\AccessControlService;
use Illuminate\Http\Request;
use Closure;
use Bouncer;
use Cache;
use Auth;

class AccessRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param string $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $keyCache = $this->generateCacheKey($request, $role);
        $user = Auth::user();

        if (!$user->isActive()) {
            return redirect()->route('logout')
                ->with('error', t('home.YourAccountIsNotActive'));
        }

        $access = \Cache::remember($keyCache, 15, function () use ($user, $role) {
            return $user->hasRole(...explode('|', $role));
        });

        $access ?: abort(403, t('home.AccessDenied'));

        return $next($request);
    }

    /**
     * @param Request $request
     * @param string $role
     * @return string
     */
    protected function generateCacheKey(Request $request, string $role): string
    {
        return md5($request->url() . $role . serialize(Auth::user()));
    }
}
