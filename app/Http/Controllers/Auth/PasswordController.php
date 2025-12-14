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
     * Display the password change form.
     */
    public function edit(Request $request)
    {
        return view('password.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $request->user()->update([
            'katalaluan' => $validated['password'],
            //  'katalaluan' => Hash::make($validated['password']),
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Kata laluan berjaya dikemaskini!');
    }
}
