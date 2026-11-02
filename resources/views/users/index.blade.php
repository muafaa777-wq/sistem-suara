@extends('layout')
@section('title', 'Petugas')
@section('content')
<div class="card">
  <h2 style="margin-bottom:12px">Daftar petugas</h2>
  <div class="scroll"><table>
    <thead><tr><th>Nama</th><th>Email</th><th>Wilayah</th><th>Peran</th><th></th></tr></thead>
    <tbody>
    @foreach($users as $u)
      <tr><td><b>{{ $u->name }}</b></td><td>{{ $u->email }}</td><td>{{ $u->area }}</td><td>{{ $u->role }}</td>
        <td>@if($u->id !== auth()->id())
          <form class="inline" method="POST" action="{{ route('users.destroy', $u) }}" onsubmit="return confirm('Hapus akun {{ e($u->name) }}?')">
            @csrf @method('DELETE')<button class="s d" type="submit">Hapus</button></form>
        @endif</td></tr>
    @endforeach
    </tbody>
  </table></div>
</div>
<div class="card">
  <h2 style="margin-bottom:12px">Tambah akun petugas</h2>
  <form method="POST" action="{{ route('users.store') }}">
    @csrf
    <div class="grid">
      <div><label>Nama lengkap</label><input name="name" value="{{ old('name') }}" style="width:100%" required></div>
      <div><label>Email (untuk login)</label><input type="email" name="email" value="{{ old('email') }}" style="width:100%" required></div>
      <div><label>Kata sandi (min. 8 karakter)</label><input type="password" name="password" style="width:100%" required minlength="8" autocomplete="new-password"></div>
      <div><label>Wilayah pengawasan</label><select name="area" style="width:100%">@foreach($areas as $a)<option>{{ $a }}</option>@endforeach</select></div>
      <div><label>Peran</label><select name="role" style="width:100%"><option value="petugas">Petugas (input suara)</option><option value="admin">Admin (kelola semua)</option></select></div>
    </div>
    <button class="p" type="submit" style="margin-top:16px">Buat akun</button>
  </form>
</div>
@endsection
