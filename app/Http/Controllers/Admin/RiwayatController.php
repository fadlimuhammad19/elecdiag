<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Diagnosa;
use App\Models\Komponen;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    private function query(Request $request)
    {
        return Diagnosa::with('kerusakan.komponen', 'user')
            ->when($request->q, function ($q, $cari) {
                $q->whereHas('user', function ($u) use ($cari) {
                    $u->where('name', 'like', "%{$cari}%")
                      ->orWhere('email', 'like', "%{$cari}%");
                });
            })
            ->when($request->komponen_id, function ($q, $id) {
                $q->whereHas('kerusakan', fn ($k) => $k->where('komponen_id', $id));
            })
            ->when($request->prioritas, function ($q, $p) {
                $q->whereHas('kerusakan', fn ($k) => $k->where('prioritas', $p));
            })
            ->when($request->dari, fn ($q, $t) => $q->whereDate('created_at', '>=', $t))
            ->when($request->sampai, fn ($q, $t) => $q->whereDate('created_at', '<=', $t))
            ->latest();
    }

    public function index(Request $request)
    {
        $riwayat   = $this->query($request)->paginate(15)->withQueryString();
        $komponens = Komponen::orderBy('nama')->get();

        return view('admin.riwayat.index', compact('riwayat', 'komponens'));
    }

    public function ekspor(Request $request)
    {
        $nama = 'riwayat-diagnosa-' . now()->format('Ymd-His') . '.csv';
        $rows = $this->query($request)->get();

        return response()->streamDownload(function () use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF"); // BOM supaya Excel membaca UTF-8

            fputcsv($out, ['ID', 'Tanggal', 'Pengguna', 'Email', 'Komponen', 'Hasil Diagnosa', 'Prioritas', 'Keyakinan (%)'], ';');

            foreach ($rows as $d) {
                fputcsv($out, [
                    $d->id,
                    $d->created_at->format('Y-m-d H:i'),
                    $d->user->name ?? 'Tamu',
                    $d->user->email ?? '-',
                    $d->kerusakan->komponen->nama ?? '-',
                    $d->kerusakan->nama ?? '-',
                    $d->kerusakan->prioritas ?? '-',
                    number_format($d->nilai_cf * 100, 1),
                ], ';');
            }

            fclose($out);
        }, $nama, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}