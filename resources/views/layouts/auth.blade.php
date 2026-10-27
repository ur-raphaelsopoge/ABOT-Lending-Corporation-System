<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ABOT Lending Corporation System')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <main class="auth-layout">
        <section class="brand-panel">
            <div class="brand-inner">
                <div class="brand-logo">
                    <img src="{{ asset('pictures/gurl.jpg') }}" alt="College of Information Technology">
                </div>
                <div class="brand-copy">
                    <p class="eyebrow eyebrow-gold">@yield('brand_eyebrow', 'Academic Quality')</p>
                    <h1>@yield('brand_title', 'ABOT Lending Corporation System')</h1>
                    <p class="lead">@yield('brand_lead', 'Build, apply, review, approve, disburse, and analyze loan accounts and borrower requests in one secure institutional workspace.')</p>
                    <ul class="chip-list">
                        <li class="chip">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.8 4.7L18.5 9.5l-4.7 1.8L12 16l-1.8-4.7L5.5 9.5l4.7-1.8L12 3z"/><path d="M18 15l.8 2.2L21 18l-2.2.8L18 21l-.8-2.2L15 18l2.2-.8L18 15z"/></svg>
                            Fast Loan
                        </li>
                        <li class="chip">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M8.5 12.5l2.5 2.5 4.5-5"/></svg>
                            Approval Workflow
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-wrap">
                <img class="form-logo" src="{{ asset('pictures/ccdi.png') }}" alt="College seal">
                <p class="eyebrow eyebrow-navy">@yield('eyebrow')</p>
                <h2>@yield('heading')</h2>
                <p class="subtitle">@yield('subtitle')</p>

                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error" role="alert">{{ session('error') }}</div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info" role="status">{{ session('info') }}</div>
                @endif

                @yield('content')
            </div>
        </section>
    </main>

    @include('partials.password-toggle')
</body>
</html>
