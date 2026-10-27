<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Mahalakshmi</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Playfair+Display:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
</head>

<body class="admin-login-page">
    <main class="admin-login-card">
        <img src="{{ asset('/images/logo_final.png') }}" alt="Mahalakshmi" class="admin-login-logo">
        <p class="eyebrow">MAHALAKSHMI ADMINISTRATION</p>
        <h1>Admin Login</h1>
        <p class="admin-login-intro">Sign in with your administrator account to continue.</p>

        <form method="POST" action="{{ route('admin.login') }}" class="admin-login-form">
            @csrf

            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" required autofocus>
            @error('email')
                <p class="admin-login-error">{{ $message }}</p>
            @enderror

            <label for="password">Password</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            @error('password')
                <p class="admin-login-error">{{ $message }}</p>
            @enderror

            <button type="submit" class="admin-login-submit">Sign in</button>
        </form>
    </main>
</body>

</html>
