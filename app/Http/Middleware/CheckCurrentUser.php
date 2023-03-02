<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenException;
use Closure;
use Illuminate\Http\Request;

class CheckCurrentUser
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \App\Exceptions\ForbiddenException
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->route('user')->id != auth()->user()->id) {
            throw new ForbiddenException();
        }

        return $next($request);
    }
}
