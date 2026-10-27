@extends('layouts.auth')

@section('title', 'Reset password | ABOT Lending Corporation System')
@section('eyebrow', 'Account recovery')
@section('heading', 'Set a new password')
@section('subtitle', 'Choose a strong password you have not used before.')

@section('content')
    <form method="POST" action="{{ route('password.update') }}" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label for="email">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $email) }}"
                autocomplete="email"
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
            >
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">New password</label>
            <div class="password-wrap">
                <input type="password" id="password" name="password" placeholder="Create a new password" autocomplete="new-password" autofocus class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                <button type="button" class="toggle-password" data-toggle-password="password" aria-label="Show password" aria-pressed="false">
                    @include('partials.eye-icon')
                </button>
            </div>
            @error('password')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm new password</label>
            <div class="password-wrap">
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Repeat your new password" autocomplete="new-password">
                <button type="button" class="toggle-password" data-toggle-password="password_confirmation" aria-label="Show password" aria-pressed="false">
                    @include('partials.eye-icon')
                </button>
            </div>
        </div>

        <p class="hint">Use at least 8 characters with letters and numbers.</p>

        <button type="submit" class="btn btn-primary">Reset password</button>
    </form>

    <p class="switch-auth"><a class="text-link" href="{{ route('login') }}">Back to sign in</a></p>
@endsection
