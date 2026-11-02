@extends('layout')
@section('title', 'Kandidat')
@section('content')
@php $isAdmin = auth()->user()->role === 'admin'; @endphp
<div class="card">
  <h2 style="margin-bottom:6px">Kandidat / pilihan</h2>
  @forelse($candidates as $i => $c)
    <div class="cl">
      <div class="no" style="background:{{ $c->color }}">{{ $i + 1 }}</div>
      @if($isAdmin)
        <form class="n row" method="POST" action="{{ route('candidates.update', $c) }}" style="flex:1;flex-wrap:nowrap">
          @csrf @method('PUT')
          <input name="name" value="{{ $c->name }}" required maxlength="100" style="flex:1;min-width:0">
          <button class="s" type="submit">Simpan</button>
        </form>
        <form method="POST" action="{{ route('candidates.destroy', $c) }}"
              onsubmit="return confirm('Hapus kandidat ini beserta suaranya di semua TPS?')">
          @csrf @method('DELETE')<button class="s d" type="submit">Hapus</button>
        </form>
      @else
        <div class="n">{{ $c->name }}</div>
      @endif
    </div>
  @empty
    <p class="empty">Belum ada kandidat.</p>
  @endforelse

  @if($isAdmin)
    <form class="row" method="POST" action="{{ route('candidates.store') }}" style="margin-top:14px">
      @csrf
      <input class="grow" name="name" placeholder="Nama kandidat / pilihan" required maxlength="100">
      <button class="p" type="submit">Tambah</button>
    </form>
  @else
    <p class="hint" style="margin-top:12px">Hanya admin yang dapat mengubah daftar kandidat.</p>
  @endif
</div>
@endsection
