<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnsureOperatorToken
{
    /**
     * Ensure an operator token exists in the session for per-device broadcasting.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('operator_token')) {
            $request->session()->put('operator_token', Str::uuid()->toString());
        }

        return $next($request);
    }
}
