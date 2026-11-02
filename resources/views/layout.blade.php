<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <title>@yield('title') · Perhitungan Suara</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
  <div class="top">
    <div class="in">
      <div class="logo"><img src="{{ asset('images/logo-bawaslu.png') }}" alt="Logo Bawaslu"></div>
      <div>
        <h1>Perhitungan Suara</h1><small>{{ auth()->user()->name }} · {{ auth()->user()->area }}</small>
      </div>
      <a class="btn" href="{{ route('account.edit') }}">Akun</a>
      <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Keluar</button></form>
    </div>
  </div>
  <nav>
    <div class="in">
      <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'on' : '' }}">Ringkasan</a>
      <a href="{{ route('tps.create') }}" class="{{ request()->routeIs('tps.create', 'tps.edit') ? 'on' : '' }}">Input
        Suara</a>
      <a href="{{ route('candidates.index') }}"
        class="{{ request()->routeIs('candidates.*') ? 'on' : '' }}">Kandidat</a>
      <a href="{{ route('tps.index') }}" class="{{ request()->routeIs('tps.index') ? 'on' : '' }}">Data TPS</a>
      @if(auth()->user()->role === 'admin')
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'on' : '' }}">Petugas</a>
      @endif
    </div>
  </nav>
  <main>
    @if(session('ok'))
    <div class="flash">{{ session('ok') }}</div>@endif
    @if($errors->any())
      <div class="flash bad">@foreach($errors->all() as $e)
      <div>{{ $e }}</div>@endforeach
    </div>@endif
    @yield('content')
  </main>
</body>

</html>