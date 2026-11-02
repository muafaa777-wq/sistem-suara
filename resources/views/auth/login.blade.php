<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>Masuk · Sistem Perhitungan Suara</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
  <div class="hero">
    <div class="logo"><img src="{{ asset('images/logo-bawaslu.png') }}" alt="Logo Bawaslu"></div>
    <h1>Sistem Perhitungan Suara</h1>
    <div class="bar"></div>
  </div>
  <div class="login">
    <div class="card">
      <h2>Masuk sebagai petugas</h2>
      <form method="POST" action="{{ url('/login') }}">
        @csrf
        <label>Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        <label>Kata sandi</label>
        <input type="password" name="password" required autocomplete="current-password">
        <label class="check" style="margin-bottom:14px"><input type="checkbox" name="remember" value="1"> Ingat
          saya</label>
        @error('email')
        <div class="err">{{ $message }}</div>@enderror
        <button class="p" type="submit">Masuk ke Dashboard</button>
      </form>
    </div>
    <p class="note">Belum punya akun? Hubungi admin untuk dibuatkan akun petugas.</p>
  </div>
</body>

</html>