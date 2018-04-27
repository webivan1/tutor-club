<?php

namespace App\Http\Middleware;

use App\Entity\Keywords;
use Closure;

class TranslationSync
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
        $response = $next($request);

        Keywords::syncKeys(t(null));

        return $response;
    }
}
