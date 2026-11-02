@extends('layout')
@section('title', 'Akun')
@section('content')
<div class="card" style="max-width:460px">
  <h2 style="margin-bottom:4px">Akun saya</h2>
  <p class="hint" style="margin:0 0 16px">{{ auth()->user()->name }} · {{ auth()->user()->email }}</p>
  <form method="POST" action="{{ route('account.update') }}">
    @csrf @method('PUT')
    <label>Kata sandi lama</label><input type="password" name="current_password" style="width:100%;margin-bottom:12px" required autocomplete="current-password">
    <label>Kata sandi baru (min. 8 karakter)</label><input type="password" name="password" style="width:100%;margin-bottom:12px" required minlength="8" autocomplete="new-password">
    <label>Ulangi kata sandi baru</label><input type="password" name="password_confirmation" style="width:100%;margin-bottom:16px" required autocomplete="new-password">
    <button class="p" type="submit">Ubah kata sandi</button>
  </form>
</div>
@endsection
