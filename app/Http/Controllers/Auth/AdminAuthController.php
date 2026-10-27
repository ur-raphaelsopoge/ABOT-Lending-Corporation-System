<?php

namespace App\Http\Controllers\Auth;

use App\Models\AuditLedger;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController
{
    public function create(): View
    {
        return view('admin.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'username.required' => 'Enter your admin username.',
            'password.required' => 'Enter your password.',
        ]);

        $credentials = [
            'username' => strtolower(trim($data['username'])),
            'password' => $data['password'],
        ];

        if (! Auth::guard('admin')->attempt($credentials)) {
            return back()
                ->withInput($request->only('username'))
                ->withErrors(['username' => 'The username or password is incorrect.']);
        }

        $request->session()->regenerate();

        AuditLedger::record(null, 'admin_login', 'Administrator signed in.', Auth::guard('admin')->id());

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        AuditLedger::record(null, 'admin_logout', 'Administrator signed out.', Auth::guard('admin')->id());

        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'You have been signed out.');
    }
}
