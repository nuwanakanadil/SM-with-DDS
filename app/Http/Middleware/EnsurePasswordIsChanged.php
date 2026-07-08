<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordIsChanged
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response|RedirectResponse
    {
        $user = $request->user();

        if (! $user || ! $user->must_change_password) {
            return $next($request);
        }

        if (in_array($request->route()?->getName(), ['profile.edit', 'password.update', 'logout'], true)) {
            return $next($request);
        }

        return redirect()
            ->route('profile.edit')
            ->with('warning', 'Please change your temporary password before continuing.')
            ->withFragment('password');
    }
}
