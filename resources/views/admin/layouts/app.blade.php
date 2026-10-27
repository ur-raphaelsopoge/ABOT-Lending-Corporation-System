<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') | Admin | ABOT Lending Corporation System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>
    @php
        $currentAdmin = auth('admin')->user();
        $navItems = [
            ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/>'],
            ['route' => 'admin.members.index', 'label' => 'Member List', 'icon' => '<circle cx="9" cy="8" r="3.5"/><path d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6"/><path d="M16 4.6a3.5 3.5 0 010 6.8M18.5 14.4c1.8.7 3 2.4 3 5.6"/>'],
            ['route' => 'admin.capital-shares.index', 'label' => 'Capital Shares', 'icon' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v10M9.5 9.5c0-1 1.1-1.8 2.5-1.8s2.5.8 2.5 1.8-1.1 1.6-2.5 1.9-2.5.9-2.5 1.9 1.1 1.8 2.5 1.8 2.5-.8 2.5-1.8"/>'],
            ['route' => 'admin.receipt-logs.index', 'label' => 'Receipt Log', 'icon' => '<path d="M6 3h12v18l-3-2-3 2-3-2-3 2V3z"/><path d="M9 8h6M9 12h6"/>'],
            ['route' => 'admin.loans.index', 'label' => 'Loan Portfolio', 'icon' => '<rect x="3" y="6" width="18" height="12" rx="2"/><circle cx="12" cy="12" r="2.5"/><path d="M6.5 9.5v.01M17.5 14.5v.01"/>'],
            ['route' => 'admin.audit-ledger.index', 'label' => 'Audit Ledger', 'icon' => '<path d="M12 3l8 3v6c0 4.5-3.2 8-8 9-4.8-1-8-4.5-8-9V6l8-3z"/><path d="M8.5 12l2.5 2.5 4.5-5"/>'],
        ];
    @endphp

    <div class="shell">
        <input type="checkbox" id="nav-toggle" class="nav-toggle" aria-label="Toggle navigation">
        <label for="nav-toggle" class="scrim"></label>

        <aside class="sidebar" aria-label="Admin navigation">
            <a href="{{ route('admin.dashboard') }}" class="brand">
                <img src="{{ asset('pictures/ccdi.png') }}" alt="College seal">
                <span>ABOT Lending Corporation System</span>
            </a>

            <p class="nav-label">Admin panel</p>

            <nav class="nav">
                @foreach ($navItems as $item)
                    <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['route']) ? 'is-active' : '' }}" @if (request()->routeIs($item['route'])) aria-current="page" @endif>
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $item['icon'] !!}</svg>
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="sidebar-foot">
                <div class="account">
                    <span class="avatar">{{ mb_strtoupper(mb_substr($currentAdmin->name, 0, 1)) }}</span>
                    <div class="account-meta">
                        <strong>{{ $currentAdmin->name }}</strong>
                        <span>Administrator</span>
                    </div>
                </div>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout">
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 4H5a1 1 0 00-1 1v14a1 1 0 001 1h4"/><path d="M16 8l4 4-4 4M20 12H9"/></svg>
                        Log out
                    </button>
                </form>
            </div>
        </aside>

        <div class="workspace">
            <header class="topbar">
                <label for="nav-toggle" class="menu-btn" aria-label="Open navigation">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
                </label>
                <h2 class="topbar-title">@yield('title')</h2>
                <span class="topbar-user">{{ $currentAdmin->name }}</span>
            </header>

            <main class="content">
                @if (session('status'))
                    <div class="alert alert-success" role="status">{{ session('status') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-error" role="alert">{{ session('error') }}</div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
