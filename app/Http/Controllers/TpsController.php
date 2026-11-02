<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\TpsResult;
use App\Models\Vote;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TpsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $rows = TpsResult::with(['votes', 'user'])
            ->when($q !== '', fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->get();

        return view('tps.index', [
            'rows' => $rows,
            'candidates' => Candidate::orderBy('id')->get(),
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('tps.form', [
            'tps' => new TpsResult(),
            'candidates' => Candidate::orderBy('id')->get(),
            'votes' => collect(),
        ]);
    }

    public function edit(TpsResult $tps)
    {
        return view('tps.form', [
            'tps' => $tps,
            'candidates' => Candidate::orderBy('id')->get(),
            'votes' => $tps->votes->pluck('count', 'candidate_id'),
        ]);
    }

    public function store(Request $request)
    {
        return $this->save($request, new TpsResult(), true);
    }

    public function update(Request $request, TpsResult $tps)
    {
        return $this->save($request, $tps, false);
    }

    public function destroy(TpsResult $tps)
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Hanya admin yang dapat menghapus data TPS.');
        $tps->delete();

        return back()->with('ok', 'Data TPS dihapus.');
    }

    public function export()
    {
        $candidates = Candidate::orderBy('id')->get();

        return response()->streamDownload(function () use ($candidates) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // agar terbaca benar di Excel
            fputcsv($out, array_merge(['TPS'], $candidates->pluck('name')->all(), ['Tidak sah', 'DPT', 'Petugas']), ',', '"', '\\');
            foreach (TpsResult::with(['votes', 'user'])->orderBy('name')->get() as $t) {
                $m = $t->votes->pluck('count', 'candidate_id');
                fputcsv($out, array_merge(
                    [$t->name],
                    $candidates->map(fn($c) => $m[$c->id] ?? 0)->all(),
                    [$t->invalid, $t->dpt, $t->user->name ?? '-']
                ), ',', '"', '\\');
            }
            fclose($out);
        }, 'rekap-suara-' . date('Ymd-His') . '.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function pdf(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $rows = TpsResult::with(['votes', 'user'])
            ->when($q !== '', fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderBy('name')
            ->get();

        $pdf = Pdf::loadView('tps.pdf', [
            'rows' => $rows,
            'candidates' => Candidate::orderBy('id')->get(),
            'q' => $q,
            'logo' => public_path('images/logo-bawaslu.png'),
        ])->setPaper('a4', 'landscape');

        return $pdf->download('rekap-suara-' . date('Ymd-His') . '.pdf');
    }

    private function save(Request $request, TpsResult $tps, bool $isNew)
    {
        $candidateIds = Candidate::pluck('id');
        if ($candidateIds->isEmpty()) {
            return back()->withErrors(['name' => 'Tambahkan kandidat terlebih dahulu.'])->withInput();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:50', Rule::unique('tps_results', 'name')->ignore($tps->id)],
            'dpt' => ['nullable', 'integer', 'min:0'],
            'invalid' => ['nullable', 'integer', 'min:0'],
            'votes' => ['array'],
            'votes.*' => ['nullable', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama TPS wajib diisi.',
            'name.unique' => 'Nama TPS ini sudah ada. Gunakan menu Edit untuk mengubahnya.',
            '*.integer' => 'Isian angka harus berupa bilangan bulat.',
            '*.min' => 'Angka tidak boleh negatif.',
        ]);

        $votes = [];
        $sum = 0;
        foreach ($candidateIds as $id) {
            $votes[$id] = (int) ($data['votes'][$id] ?? 0);
            $sum += $votes[$id];
        }
        $dpt = (int) ($data['dpt'] ?? 0);
        $invalid = (int) ($data['invalid'] ?? 0);

        if ($dpt > 0 && $sum + $invalid > $dpt) {
            return back()->withErrors(['dpt' => 'Total suara (' . ($sum + $invalid) . ") melebihi jumlah pemilih (DPT) {$dpt}."])->withInput();
        }

        DB::transaction(function () use ($tps, $data, $dpt, $invalid, $votes, $isNew) {
            $tps->fill(['name' => $data['name'], 'dpt' => $dpt, 'invalid' => $invalid]);
            if ($isNew) {
                $tps->user_id = auth()->id();
            }
            $tps->save();
            foreach ($votes as $candidateId => $count) {
                Vote::updateOrCreate(
                    ['tps_result_id' => $tps->id, 'candidate_id' => $candidateId],
                    ['count' => $count]
                );
            }
        });

        return redirect()->route('tps.index')->with('ok', "Suara {$tps->name} tersimpan.");
    }
}