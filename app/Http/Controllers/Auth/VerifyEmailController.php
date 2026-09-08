<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        $redirectUrl = $request->user()->hasAnyRole(['admin', 'staff'])
            ? route('admin.dashboard', absolute: false).'?verified=1'
            : route('public.results', absolute: false).'?verified=1';

        if ($request->user()->hasVerifiedEmail()) {
            return redirect($redirectUrl);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect($redirectUrl);
    }
}
