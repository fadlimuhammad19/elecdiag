<?php

namespace App\Http\Controllers;

use App\Models\Diagnosa;
use App\Models\Gejala;
use App\Models\Kategori;
use App\Models\Komponen;
use App\Services\CertaintyFactorService;
use Illuminate\Http\Request;

class DiagnosaController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::with('komponens')->orderBy('kode')->get();

        return view('diagnosa.index', compact('kategoris'));
    }

    public function wizard(Komponen $komponen)
    {
        $gejalas = Gejala::whereHas('rules.kerusakan', fn ($q) => $q->where('komponen_id', $komponen->id))
            ->orderBy('kode')
            ->get();

        return view('diagnosa.wizard', compact('komponen', 'gejalas'));
    }

    public function proses(Request $request, CertaintyFactorService $service)
    {
        $request->validate([
            'komponen_id' => 'required|exists:komponens,id',
            'jawaban'     => 'nullable|array',
            'jawaban.*'   => 'in:ya,tidak,tahu',
        ]);

        $gejalaUser = collect($request->jawaban ?? [])
            ->filter(fn ($j) => $j === 'ya')
            ->map(fn () => 1.0)
            ->toArray();

        if (empty($gejalaUser)) {
            return back()->with('error', 'Jawab "Ya" pada minimal satu gejala agar bisa didiagnosa.');
        }

        $hasil = $service->hitung($gejalaUser, (int) $request->komponen_id);

        if (empty($hasil)) {
            return back()->with('error', 'Gejala yang dipilih tidak cocok dengan kerusakan manapun.');
        }

        $teratas = $hasil[0];

        $diagnosa = Diagnosa::create([
            'user_id'      => auth()->id(),
            'kerusakan_id' => $teratas['kerusakan_id'],
            'nilai_cf'     => $teratas['cf'],
            'hasil_semua'  => $hasil,
        ]);

        $pivot = [];
        foreach ($gejalaUser as $gejalaId => $cfUser) {
            $pivot[$gejalaId] = ['cf_user' => $cfUser];
        }
        $diagnosa->gejalas()->attach($pivot);

        return redirect()->route('diagnosa.hasil', $diagnosa);
    }

    public function hasil(Diagnosa $diagnosa)
    {
        $this->izinkan($diagnosa);

        $diagnosa->load('gejalas', 'kerusakan.komponen', 'kerusakan.rekomendasis');

        return view('diagnosa.hasil', compact('diagnosa'));
    }

    public function cetak(Diagnosa $diagnosa)
    {
        $this->izinkan($diagnosa);

        $diagnosa->load('gejalas', 'kerusakan.komponen', 'kerusakan.rekomendasis', 'user');

        return view('diagnosa.cetak', compact('diagnosa'));
    }

    public function riwayat()
    {
        $riwayat = Diagnosa::with('kerusakan.komponen')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('diagnosa.riwayat', compact('riwayat'));
    }

    private function izinkan(Diagnosa $diagnosa): void
    {
        abort_unless(
            $diagnosa->user_id === auth()->id() || auth()->user()->role === 'admin',
            403
        );
    }
}