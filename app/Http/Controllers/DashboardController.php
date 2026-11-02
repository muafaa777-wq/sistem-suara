<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\TpsResult;

class DashboardController extends Controller
{
    public function index()
    {
        $candidates = Candidate::withSum('votes as total', 'count')->orderBy('id')->get();
        $candidates->each(fn ($c) => $c->total = (int) $c->total);

        return view('dashboard', [
            'ranking' => $candidates->sortByDesc('total')->values(),
            'valid' => (int) $candidates->sum('total'),
            'tpsCount' => TpsResult::count(),
            'invalid' => (int) TpsResult::sum('invalid'),
            'dpt' => (int) TpsResult::sum('dpt'),
        ]);
    }
}
