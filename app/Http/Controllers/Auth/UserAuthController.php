<?php

namespace App\Http\Controllers\Auth;

use App\Models\AuditLedger;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserAuthController
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'login' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
        ], [
            'login.required' => 'Enter your borrower ID or email.',
            'password.required' => 'Enter your password.',
        ]);

        $login = trim($data['login']);
        $credentials = str_contains($login, '@')
            ? ['email' => strtolower($login)]
            : ['borrower_id' => strtoupper($login)];
        $credentials['password'] = $data['password'];

        if (! Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('login'))
                ->withErrors(['login' => 'The borrower ID, email, or password is incorrect.']);
        }

        $request->session()->regenerate();

        AuditLedger::record(Auth::guard('web')->id(), 'login', 'Borrower signed in.');

        return redirect()->intended(route('home'));
    }

    public function showRegister(): View
    {
        return view('auth.register', [
            'loanTypes' => User::LOAN_TYPES,
            'idTypes' => User::ID_TYPES,
            'employmentStatuses' => User::EMPLOYMENT_STATUSES,
        ]);
    }

    public function register(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
            'id_number' => strtoupper(trim((string) $request->input('id_number'))),
            'phone_number' => $this->normalizePhone((string) $request->input('phone_number')),
            'emergency_contact_phone' => $this->normalizePhone((string) $request->input('emergency_contact_phone')),
            'monthly_income' => preg_replace('/[^\d.]/', '', (string) $request->input('monthly_income')),
        ]);

        $data = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'date_of_birth' => [
                'required',
                'date_format:Y-m-d',
                'after:1900-01-01',
                'before_or_equal:' . now()->subYears(18)->toDateString(),
            ],
            'phone_number' => ['required', 'regex:/^09\d{9}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'address' => ['required', 'string', 'min:10', 'max:255'],
            'id_type' => ['required', Rule::in(array_keys(User::ID_TYPES))],
            'id_number' => [
                'required',
                'string',
                'min:5',
                'max:40',
                'regex:/^[A-Z0-9\-]+$/',
                Rule::unique('users', 'id_number')->where('id_type', $request->input('id_type')),
            ],
            'employment_status' => ['required', Rule::in(array_keys(User::EMPLOYMENT_STATUSES))],
            'monthly_income' => ['required', 'numeric', 'min:0', 'max:9999999'],
            'emergency_contact_name' => ['required', 'string', 'min:3', 'max:150'],
            'emergency_contact_relationship' => ['required', 'string', 'min:3', 'max:60'],
            'emergency_contact_phone' => ['required', 'regex:/^09\d{9}$/', 'different:phone_number'],
            'loan_type' => ['required', Rule::in(array_keys(User::LOAN_TYPES))],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'name.required' => 'Enter your full name.',
            'date_of_birth.required' => 'Enter your date of birth.',
            'date_of_birth.date_format' => 'Enter a valid date of birth.',
            'date_of_birth.after' => 'Enter a valid date of birth.',
            'date_of_birth.before_or_equal' => 'You must be at least 18 years old to register.',
            'phone_number.required' => 'Enter your phone number.',
            'phone_number.regex' => 'Enter a valid mobile number, for example 0917 123 4567.',
            'email.required' => 'Enter your email address.',
            'email.email' => 'Enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'address.required' => 'Enter your residential address.',
            'address.min' => 'Enter your complete residential address.',
            'id_type.required' => 'Choose a valid ID type.',
            'id_type.in' => 'Choose a valid ID type.',
            'id_number.required' => 'Enter your ID number.',
            'id_number.regex' => 'Use only letters, numbers, and hyphens in your ID number.',
            'id_number.unique' => 'This ID has already been used to register an account.',
            'employment_status.required' => 'Choose your employment status.',
            'employment_status.in' => 'Choose a valid employment status.',
            'monthly_income.required' => 'Enter your monthly income.',
            'monthly_income.numeric' => 'Enter your monthly income as a number.',
            'emergency_contact_name.required' => 'Enter your emergency contact name.',
            'emergency_contact_relationship.required' => 'Enter your relationship to the emergency contact.',
            'emergency_contact_phone.required' => 'Enter your emergency contact number.',
            'emergency_contact_phone.regex' => 'Enter a valid mobile number, for example 0917 123 4567.',
            'emergency_contact_phone.different' => 'The emergency contact number must be different from your own.',
            'loan_type.required' => 'Choose a loan type.',
            'loan_type.in' => 'Choose a valid loan type.',
            'password.required' => 'Create a password.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'Your password must be at least 8 characters.',
            'password.letters' => 'Your password must include at least one letter.',
            'password.numbers' => 'Your password must include at least one number.',
        ]);

        $user = User::create($data);
        $user->refresh();

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        AuditLedger::record($user->id, 'register', 'Borrower account created (' . $user->borrower_id . ').');

        return redirect()
            ->route('home')
            ->with('status', 'Welcome, ' . $user->name . '! Your Borrower ID is ' . $user->borrower_id . '.');
    }

    public function logout(Request $request): RedirectResponse
    {
        AuditLedger::record(Auth::guard('web')->id(), 'logout', 'Borrower signed out.');

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'You have been signed out.');
    }

    public function showForgotPassword(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ], [
            'email.required' => 'Enter your email address.',
            'email.email' => 'Enter a valid email address.',
        ]);

        $status = PasswordBroker::broker('users')->sendResetLink([
            'email' => strtolower(trim($data['email'])),
        ]);

        if ($status === PasswordBroker::RESET_THROTTLED) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Please wait a moment before requesting another reset link.']);
        }

        return back()->with('status', 'If that email address is registered, a password reset link has been sent.');
    }

    public function showResetPassword(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'email.required' => 'Enter your email address.',
            'email.email' => 'Enter a valid email address.',
            'password.required' => 'Create a new password.',
            'password.confirmed' => 'The password confirmation does not match.',
            'password.min' => 'Your password must be at least 8 characters.',
            'password.letters' => 'Your password must include at least one letter.',
            'password.numbers' => 'Your password must include at least one number.',
        ]);

        $status = PasswordBroker::broker('users')->reset(
            [
                'email' => strtolower(trim($data['email'])),
                'password' => $data['password'],
                'password_confirmation' => $request->input('password_confirmation'),
                'token' => $data['token'],
            ],
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                AuditLedger::record($user->id, 'password_reset', 'Password was reset.');

                event(new PasswordReset($user));
            }
        );

        if ($status !== PasswordBroker::PASSWORD_RESET) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'This password reset link is invalid or has expired.']);
        }

        return redirect()->route('login')->with('status', 'Your password has been reset. You can now sign in.');
    }

    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/[\s\-\(\)\.]/', '', $phone);

        if (str_starts_with($digits, '+63')) {
            return '0' . substr($digits, 3);
        }

        if (str_starts_with($digits, '63') && strlen($digits) === 12) {
            return '0' . substr($digits, 2);
        }

        return $digits;
    }
}
