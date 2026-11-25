<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login · Duta Nusantara</title><link rel="stylesheet" href="{{ asset('css/admin.css') }}"><script src="{{ asset('js/admin.js') }}" defer></script></head>
<body class="login-body">
<div class="login-page">
    <section class="login-brand">
        <div class="brand-lockup"><div class="brand-mark large"><span></span><i></i></div><div><strong>DUTA<br>NUSANTARA</strong></div></div>
        <h1>License Management System</h1><p>Mikroskop Biologi Digital</p>
        <div class="login-science-art"><div class="art-blob"></div><div class="microscope"><div class="microscope-tube"></div><div class="microscope-joint"></div><div class="microscope-arm"></div><div class="microscope-stage"></div><div class="microscope-foot"></div></div><div class="art-leaf leaf-one"></div><div class="art-leaf leaf-two"></div></div>
    </section>
    <section class="login-form-side"><div class="login-card">
        <div class="login-icon"><div class="brand-mark"><span></span><i></i></div></div>
        <h2>Login Administrator</h2><p class="login-subtitle">Masuk untuk mengelola serial code.</p>
        @if ($errors->any())<div class="alert error">{{ $errors->first() }}</div>@endif
        <form method="POST" action="{{ route('login.store') }}" class="login-form">@csrf
            <label class="field-label" for="login">Email atau Username</label><div class="input-with-icon"><span>♙</span><input id="login" name="login" value="{{ old('login') }}" autocomplete="username" placeholder="Email atau username" required autofocus></div>
            <label class="field-label" for="password">Password</label><div class="input-with-icon"><span>♙</span><input id="password" name="password" type="password" autocomplete="current-password" placeholder="Masukkan password" required><button type="button" data-password-toggle>◉</button></div>
            <button class="btn primary login-submit" type="submit"><span>↪</span> Login</button>
        </form>
        <div class="login-footer">Protected administrator access <span>•</span> Duta Nusantara</div>
    </div></section>
</div>
</body></html>
