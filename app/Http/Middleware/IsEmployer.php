<?php

namespace App\Http\Middleware;

use App\Helpers\APIHelper;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class IsEmployer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info(auth()->user());
        if (!auth()->user()?->is_employer) {
            return APIHelper::error("You are not authorized to perform this action", null, 403);
        }
        return $next($request);
    }
}
