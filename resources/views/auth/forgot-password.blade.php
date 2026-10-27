@extends('layouts.auth')

@section('title', 'Forgot password | ABOT Lending Corporation System')
@section('eyebrow', 'Account recovery')
@section('heading', 'Forgot your password?')
@section('subtitle', 'Enter the email address on your account and we will send you a link to reset your password.')

@section('content')
    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <div class="field">
            <label for="email">Email address</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="name@email.com"
                autocomplete="email"
                autofocus
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
            >
            @error('email')
                <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Send reset link</button>
    </form>

    <p class="switch-auth"><a class="text-link" href="{{ route('login') }}">Back to sign in</a></p>
@endsection
