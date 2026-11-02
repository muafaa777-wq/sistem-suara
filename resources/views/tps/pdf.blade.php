@php $fmt = fn($n) => number_format($n, 0, ',', '.'); @endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Rekap Suara per TPS</title>
    <style>
        @page {
            margin: 28px 32px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #16191d;
        }

        .head {
            width: 100%;
            border-bottom: 3px solid #b91c1c;
            padding-bottom: 8px;
            margin-bottom: 14px;
        }

        .head td {
            border: 0;
            padding: 0;
            vertical-align: middle;
        }

        .head img {
            width: 60px;
        }

        h1 {
            font-size: 17px;
            margin: 0;
        }

        .sub {
            color: #667085;
            margin-top: 3px;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
        }

        table.data th,
        table.data td {
            border: 1px solid #c9cdd4;
            padding: 6px 7px;
            text-align: right;
        }

        table.data th {
            background: #b91c1c;
            color: #fff;
            font-size: 10.5px;
        }

        table.data td.l,
        table.data th.l {
            text-align: left;
        }

        table.data tbody tr:nth-child(even) {
            background: #fdf2f2;
        }

        table.data tfoot td {
            font-weight: bold;
            background: #f1e3e3;
        }

        .foot {
            margin-top: 12px;
            color: #667085;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <table class="head">
        <tr>
            <td style="width:70px"><img src="{{ $logo }}" alt=""></td>
            <td>
                <h1>Rekap Suara per TPS</h1>
                <div class="sub">Sistem Perhitungan Suara &middot;
                    Bawaslu{{ $q !== '' ? ' · Filter: "' . $q . '"' : '' }}
                </div>
            </td>
        </tr>
    </table>

    @if($rows->isEmpty())
        <p>Belum ada data TPS.</p>
    @else
        <table class="data">
            <thead>
                <tr>
                    <th class="l">No</th>
                    <th class="l">TPS</th>
                    @foreach($candidates as $c)
                    <th>{{ $c->name }}</th>@endforeach
                    <th>Tidak sah</th>
                    <th>Total</th>
                    <th class="l">Petugas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $i => $r)
                    @php $m = $r->votes->pluck('count', 'candidate_id');
                    $sum = $m->sum() + $r->invalid; @endphp
                    <tr>
                        <td class="l">{{ $i + 1 }}</td>
                        <td class="l"><b>{{ $r->name }}</b></td>
                        @foreach($candidates as $c)
                        <td>{{ $fmt($m[$c->id] ?? 0) }}</td>@endforeach
                        <td>{{ $fmt($r->invalid) }}</td>
                        <td>{{ $fmt($sum) }}</td>
                        <td class="l">{{ $r->user->name ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td class="l" colspan="2">Total</td>
                    @foreach($candidates as $c)
                        <td>{{ $fmt($rows->sum(fn($r) => $r->votes->where('candidate_id', $c->id)->sum('count'))) }}</td>
                    @endforeach
                    <td>{{ $fmt($rows->sum('invalid')) }}</td>
                    <td>{{ $fmt($rows->sum(fn($r) => $r->votes->sum('count') + $r->invalid)) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endif

    <div class="foot">Dicetak pada {{ now()->format('d-m-Y H:i') }}</div>
</body>

</html>