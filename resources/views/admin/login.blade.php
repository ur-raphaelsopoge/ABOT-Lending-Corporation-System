<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin sign in | ABOT Lending Corporation System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="admin-auth">
    <main class="admin-card">
        <img class="admin-logo" src="{{ asset('pictures/ccdi.png') }}" alt="College seal">
        <p class="eyebrow eyebrow-navy">Administrator access</p>
        <h1>Admin sign in</h1>
        <p class="subtitle">This area is restricted to authorized administrators.</p>

        @if (session('status'))
            <div class="alert alert-success" role="status">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-error" role="alert">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}" novalidate>
            @csrf

            <div class="field">
                <label for="username">Username</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="Admin username"
                    autocomplete="username"
                    autofocus
                    class="{{ $errors->has('username') ? 'is-invalid' : '' }}"
                >
                @error('username')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>
                <div class="password-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        autocomplete="current-password"
                        class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                    >
                    <button type="button" class="toggle-password" data-toggle-password="password" aria-label="Show password" aria-pressed="false">
                        @include('partials.eye-icon')
                    </button>
                </div>
                @error('password')
                    <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Sign in as admin</button>
        </form>

        <p class="switch-auth"><a class="text-link" href="{{ route('login') }}">Borrower login</a></p>
    </main>

    @include('partials.password-toggle')
</body>
</html>
