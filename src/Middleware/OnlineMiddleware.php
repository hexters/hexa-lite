<?php

namespace Hexters\HexaLite\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OnlineMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        try {
            auth()->user()
                ->update([
                    'online_at' => now()->addMinute(5)
                ]);
        } catch (Exception $e) {
        }

        return $next($request);
    }
}
