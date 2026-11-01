<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosa;
use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Rule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $hari = in_array((int) $request->hari, [7, 14, 30]) ? (int) $request->hari : 7;

        $stat = [
            'diagnosa'  => Diagnosa::count(),
            'gejala'    => Gejala::count(),
            'kerusakan' => Kerusakan::count(),
            'aturan'    => Rule::count(),
            'pengguna'  => User::where('role', 'user')->count(),
        ];

        $tanggal = collect(range($hari - 1, 0))->map(fn ($i) => now()->subDays($i)->toDateString());

        $perHari = Diagnosa::selectRaw('DATE(created_at) as tgl, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays($hari - 1)->startOfDay())
            ->groupBy('tgl')
            ->pluck('total', 'tgl');

        $grafik = [
            'label' => $tanggal->map(fn ($d) => Carbon::parse($d)->format('d M'))->values(),
            'data'  => $tanggal->map(fn ($d) => (int) ($perHari[$d] ?? 0))->values(),
        ];

        $perPrioritas = Diagnosa::join('kerusakans', 'diagnosas.kerusakan_id', '=', 'kerusakans.id')
            ->selectRaw('kerusakans.prioritas as p, COUNT(*) as total')
            ->groupBy('kerusakans.prioritas')
            ->pluck('total', 'p');

        $donat = collect(['Tinggi', 'Sedang', 'Rendah'])
            ->map(fn ($p) => (int) ($perPrioritas[$p] ?? 0))
            ->values();

        $topKomponen = Diagnosa::join('kerusakans', 'diagnosas.kerusakan_id', '=', 'kerusakans.id')
            ->join('komponens', 'kerusakans.komponen_id', '=', 'komponens.id')
            ->selectRaw('komponens.nama, COUNT(*) as total')
            ->groupBy('komponens.id', 'komponens.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $maks = max(1, (int) $topKomponen->max('total'));

        $terbaru = Diagnosa::with('kerusakan.komponen', 'user')
            ->latest()
            ->take(6)
            ->get();

        return view('admin.dashboard', compact('stat', 'hari', 'grafik', 'donat', 'topKomponen', 'maks', 'terbaru'));
    }
}
