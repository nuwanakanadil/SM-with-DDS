<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $mustChangePassword = $request->user()?->must_change_password ?? false;

        $validated = $request->validate([
            'current_password' => [$mustChangePassword ? 'nullable' : 'required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => false,
        ]);

        if ($mustChangePassword) {
            return redirect()->route($request->user()->hasAnyRole(['admin', 'staff']) ? 'admin.dashboard' : 'dashboard')
                ->with('success', 'Password updated successfully.');
        }

        return back()->with('success', 'Password updated successfully.');
    }
}
