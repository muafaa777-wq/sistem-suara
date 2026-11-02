<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;

class CandidateController extends Controller
{
    private const COLORS = ['#dc2626', '#2563eb', '#16a34a', '#d97706', '#7c3aed', '#0891b2', '#db2777', '#65a30d'];

    private function admin(): void
    {
        abort_unless(auth()->user()->role === 'admin', 403, 'Hanya admin yang dapat mengubah kandidat.');
    }

    public function index()
    {
        return view('candidates.index', ['candidates' => Candidate::orderBy('id')->get()]);
    }

    public function store(Request $request)
    {
        $this->admin();
        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);
        Candidate::create([
            'name' => $data['name'],
            'color' => self::COLORS[Candidate::count() % count(self::COLORS)],
        ]);

        return back()->with('ok', 'Kandidat ditambahkan.');
    }

    public function update(Request $request, Candidate $candidate)
    {
        $this->admin();
        $data = $request->validate(['name' => ['required', 'string', 'max:100']]);
        $candidate->update($data);

        return back()->with('ok', 'Nama kandidat diperbarui.');
    }

    public function destroy(Candidate $candidate)
    {
        $this->admin();
        $candidate->delete(); // suara kandidat ikut terhapus (cascade)

        return back()->with('ok', 'Kandidat dihapus.');
    }
}
