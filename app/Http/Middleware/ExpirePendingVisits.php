<?php

namespace App\Http\Middleware;

use App\Services\VisiteExpirationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ExpirePendingVisits
{
    public function handle(Request $request, Closure $next): Response
    {
        VisiteExpirationService::expirer();

        return $next($request);
    }
}
