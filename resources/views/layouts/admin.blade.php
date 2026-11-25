<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'License Control Panel') · Duta Nusantara</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <script src="{{ asset('js/admin.js') }}" defer></script>
</head>
<body class="admin-body">
<div class="admin-shell">
    <aside class="sidebar" id="sidebar">
        <div class="brand">
            <div class="brand-mark"><span></span><i></i></div>
            <div class="brand-text"><strong>DUTA<br>NUSANTARA</strong><small>License Management System</small></div>
        </div>
        <nav class="nav-menu">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span class="nav-icon">⌂</span><span>Dashboard</span></a>
            <a class="nav-link {{ request()->routeIs('serial-codes.*') ? 'active' : '' }}" href="{{ route('serial-codes.index') }}"><span class="nav-icon">▤</span><span>Daftar Serial Code</span></a>
        </nav>
        <div class="sidebar-bottom">
            <div class="mini-microscope" aria-hidden="true"><div class="scope-arm"></div><div class="scope-head"></div><div class="scope-base"></div></div>
            <div class="sidebar-caption">Mikroskop<br>Biologi Digital</div>
            <div class="sidebar-wave"></div>
        </div>
    </aside>
    <main class="main-area">
        <header class="topbar">
            <button class="mobile-menu" type="button" data-menu-toggle aria-label="Toggle navigation">☰</button>
            <div class="topbar-search"><span>⌕</span><input type="search" placeholder="Cari serial code, pelanggan, atau device..." data-global-search><kbd>Ctrl K</kbd></div>
            <div class="topbar-actions">
                <button class="icon-button notification" type="button" aria-label="Notifikasi">♧<span></span></button>
                <div class="user-chip"><div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div><span>{{ auth()->user()->name }}</span><small>Administrator</small></div>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="logout-link" title="Logout" type="submit">↪</button></form>
            </div>
        </header>
        <section class="page-content">@yield('content')</section>
    </main>
</div>
</body>
</html>
