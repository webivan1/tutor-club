<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 14.04.2018
 * Time: 0:11
 */

namespace App\Http\Middleware;

use Closure;
use App\Entity\TutorProfile;
use Illuminate\Http\Request;

class BlockedTutorProfile
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $params = null)
    {
        dd($params);

        view()->share(compact('profile'));

        if ($profile->isBlocked()) {
            abort(403);
        }

        return $next($request);
    }
}