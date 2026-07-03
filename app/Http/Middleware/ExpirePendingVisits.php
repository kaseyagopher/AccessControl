<?php

namespace App\Http\Middleware;

use App\Services\VisiteExpirationService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ExpirePendingVisits
{
    private const CACHE_KEY = 'visites_expiration_checked';

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && ! Cache::has(self::CACHE_KEY)) {
            VisiteExpirationService::expirer();
            Cache::put(self::CACHE_KEY, true, now()->addMinutes(2));
        }

        return $next($request);
    }
}
