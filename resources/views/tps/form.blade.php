@extends('layout')
@section('title', 'Input Suara')
@section('content')
<div class="card">
  <h2 style="margin-bottom:14px">{{ $tps->exists ? 'Edit suara '.$tps->name : 'Input suara TPS' }}</h2>
  @if($candidates->isEmpty())
    <div class="empty"><p>Belum ada kandidat.</p><a class="btn p" href="{{ route('candidates.index') }}">Ke halaman Kandidat</a></div>
  @else
  <form id="f" method="POST" action="{{ $tps->exists ? route('tps.update', $tps) : route('tps.store') }}">
    @csrf
    @if($tps->exists) @method('PUT') @endif
    <div class="row">
      <div class="grow"><label>Nama / nomor TPS</label>
        <input name="name" style="width:100%" placeholder="mis. TPS 001" value="{{ old('name', $tps->name) }}" required maxlength="50"></div>
      <div class="grow"><label>Jumlah pemilih / DPT (opsional)</label>
        <input type="number" min="0" name="dpt" style="width:100%" value="{{ old('dpt', $tps->dpt ?: '') }}"></div>
    </div>
    <div class="grid" style="margin-top:16px">
      @foreach($candidates as $c)
        <div><label><i class="dot" style="background:{{ $c->color }}"></i>{{ $c->name }}</label>
          <input class="vote" type="number" min="0" step="1" name="votes[{{ $c->id }}]" style="width:100%" placeholder="0"
                 value="{{ old('votes.'.$c->id, $votes[$c->id] ?? '') }}"></div>
      @endforeach
      <div><label>Suara tidak sah</label>
        <input type="number" min="0" step="1" name="invalid" style="width:100%" placeholder="0" value="{{ old('invalid', $tps->invalid ?: '') }}"></div>
    </div>
    <div class="tot" id="tot"></div>
    <div class="row">
      <button class="p" type="submit">Simpan suara TPS</button>
      <a class="btn" href="{{ route('tps.index') }}">Batal</a>
    </div>
  </form>
  <script>
  (function(){
    var f=document.getElementById('f');
    function n(v){v=parseInt(v,10);return isNaN(v)?0:v}
    function calc(){
      var s=0;f.querySelectorAll('.vote').forEach(function(i){s+=n(i.value)});
      var inv=n(f.invalid.value),d=n(f.dpt.value),t=s+inv;
      document.getElementById('tot').innerHTML=
        '<div><b>'+s.toLocaleString('id-ID')+'</b><span>Suara sah</span></div>'+
        '<div><b>'+inv.toLocaleString('id-ID')+'</b><span>Tidak sah</span></div>'+
        '<div><b>'+t.toLocaleString('id-ID')+'</b><span>Total suara</span></div>'+
        (d?'<div><b>'+(t/d*100).toFixed(1)+'%</b><span>Partisipasi</span></div>':'');
    }
    f.addEventListener('input',calc);calc();
  })();
  </script>
  @endif
</div>
@endsection
