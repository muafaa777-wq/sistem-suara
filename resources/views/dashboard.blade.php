@extends('layout')
@section('title', 'Ringkasan')
@section('content')
@php
  $fmt = fn ($n) => number_format($n, 0, ',', '.');
  $total = $valid + $invalid;
  $tie = $ranking->count() > 1 && $ranking[0]->total === $ranking[1]->total;
@endphp
@if($ranking->isEmpty() || $tpsCount === 0)
  <div class="card empty">
    <h2>Belum ada data suara</h2>
    <p>Tambahkan kandidat, lalu input suara dari TPS pertama.</p>
    <div class="row">
      <a class="btn p" href="{{ route('candidates.index') }}">Kandidat</a>
      <a class="btn" href="{{ route('tps.create') }}">Input suara</a>
    </div>
  </div>
@else
  <div class="stats">
    <div class="stat"><span>TPS masuk</span><b>{{ $fmt($tpsCount) }}</b></div>
    <div class="stat"><span>Suara sah</span><b>{{ $fmt($valid) }}</b></div>
    <div class="stat"><span>Suara tidak sah</span><b>{{ $fmt($invalid) }}</b></div>
    <div class="stat"><span>{{ $dpt > 0 ? 'Partisipasi' : 'Total suara' }}</span><b>{{ $dpt > 0 ? number_format($total / $dpt * 100, 1, ',', '.').'%' : $fmt($total) }}</b></div>
  </div>

  <div class="lead"><small>Posisi sementara</small><br>
    <b>@if($ranking[0]->total === 0) Belum ada suara sah
       @elseif($tie) Suara teratas sama kuat
       @else {{ $ranking[0]->name }} unggul {{ number_format($ranking[0]->total / $valid * 100, 2, ',', '.') }}% @endif</b>
    @if(! $tie && $ranking->count() > 1 && $ranking[0]->total > 0)
      <br><small>Selisih {{ $fmt($ranking[0]->total - $ranking[1]->total) }} suara dari peringkat kedua</small>
    @endif
  </div>

  <div class="card two">
    <div class="donut">
      @php $off = 0; @endphp
      <svg viewBox="0 0 36 36">
        <circle cx="18" cy="18" r="15.9155" fill="none" stroke="var(--line)" stroke-width="4.5"/>
        @foreach($ranking as $c)
          @php $a = $valid ? $c->total / $valid * 100 : 0; @endphp
          <circle cx="18" cy="18" r="15.9155" fill="none" stroke="{{ $c->color }}" stroke-width="4.5"
                  stroke-dasharray="{{ round($a, 3) }} {{ round(100 - $a, 3) }}" stroke-dashoffset="{{ round(-$off, 3) }}"/>
          @php $off += $a; @endphp
        @endforeach
      </svg>
      <div><b>{{ $fmt($valid) }}</b><span>suara sah</span></div>
    </div>
    <div>
      @foreach($ranking as $i => $c)
        @php $p = $valid ? $c->total / $valid * 100 : 0; @endphp
        <div class="rk">
          <div class="h">
            <span><i class="dot" style="background:{{ $c->color }}"></i>{{ $c->name }}{{ $i === 0 && $c->total > 0 && ! $tie ? ' 🏆' : '' }}</span>
            <span><b>{{ $fmt($c->total) }}</b> · {{ number_format($p, 2, ',', '.') }}%</span>
          </div>
          <div class="track"><i style="width:{{ $p }}%;background:{{ $c->color }}"></i></div>
        </div>
      @endforeach
    </div>
  </div>
@endif
@endsection
