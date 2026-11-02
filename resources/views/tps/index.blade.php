@extends('layout')
@section('title', 'Data TPS')
@section('content')
  @php $isAdmin = auth()->user()->role === 'admin';
  $fmt = fn($n) => number_format($n, 0, ',', '.'); @endphp
  <div class="card">
    <div class="row" style="justify-content:space-between;margin-bottom:12px">
      <h2>Data per TPS</h2>
      <form method="GET" action="{{ route('tps.index') }}" class="row">
        <input name="q" value="{{ $q }}" placeholder="Cari TPS…" style="max-width:220px">
        <button type="submit">Cari</button>
      </form>
    </div>
    <div class="scroll">
      @if($rows->isEmpty())
        <p class="empty">{{ $q !== '' ? 'TPS tidak ditemukan.' : 'Belum ada data TPS.' }}</p>
      @else
        <table>
          <thead>
            <tr>
              <th>TPS</th>@foreach($candidates as $c)
              <th>{{ $c->name }}</th>@endforeach<th>Tidak sah</th>
              <th>Total</th>
              <th>Petugas</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($rows as $r)
              @php $m = $r->votes->pluck('count', 'candidate_id');
              $sum = $m->sum() + $r->invalid; @endphp
              <tr>
                <td><b>{{ $r->name }}</b></td>
                @foreach($candidates as $c)
                <td>{{ $fmt($m[$c->id] ?? 0) }}</td>@endforeach
                <td>{{ $fmt($r->invalid) }}</td>
                <td>{{ $fmt($sum) }}</td>
                <td>{{ $r->user->name ?? '-' }}</td>
                <td>
                  <a class="btn s" href="{{ route('tps.edit', $r) }}">Edit</a>
                  @if($isAdmin)
                    <form class="inline" method="POST" action="{{ route('tps.destroy', $r) }}"
                      onsubmit="return confirm('Hapus data {{ e($r->name) }}?')">
                      @csrf @method('DELETE')<button class="s d" type="submit">Hapus</button>
                    </form>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
          <tfoot>
            <tr>
              <td>Total</td>
              @foreach($candidates as $c)
              <td>{{ $fmt($rows->sum(fn($r) => $r->votes->where('candidate_id', $c->id)->sum('count'))) }}</td>@endforeach
              <td>{{ $fmt($rows->sum('invalid')) }}</td>
              <td>{{ $fmt($rows->sum(fn($r) => $r->votes->sum('count') + $r->invalid)) }}</td>
              <td></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
      @endif
    </div>
    <div class="row" style="margin-top:14px">
      <a class="btn" href="{{ route('tps.export') }}">Unduh CSV</a>
      <a class="btn" href="{{ route('tps.pdf', array_filter(['q' => $q])) }}">Unduh PDF</a>
      <a class="btn p" href="{{ route('tps.create') }}">+ Input TPS baru</a>
    </div>
  </div>
@endsection