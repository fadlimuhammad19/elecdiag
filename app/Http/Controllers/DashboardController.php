<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;

class DashboardController extends Controller
{
    public function index()
    {
        $id = auth()->id();

        $riwayatTerakhir = Diagnosa::with('kerusakan.komponen')
            ->where('user_id', $id)
            ->latest()
            ->take(5)
            ->get();

        $stat = [
            'total'  => Diagnosa::where('user_id', $id)->count(),
            'rata'   => (float) Diagnosa::where('user_id', $id)->avg('nilai_cf'),
            'tinggi' => Diagnosa::where('user_id', $id)
                ->whereHas('kerusakan', fn ($q) => $q->where('prioritas', 'Tinggi'))
                ->count(),
        ];

        return view('dashboard', compact('riwayatTerakhir', 'stat'));
    }
}
