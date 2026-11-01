<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerusakan;
use App\Models\Rekomendasi;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    public function index(Request $request)
    {
        $rekomendasis = Rekomendasi::with('kerusakan')
            ->when($request->kerusakan_id, fn ($q, $id) => $q->where('kerusakan_id', $id))
            ->orderBy('kerusakan_id')
            ->orderBy('urutan')
            ->paginate(15)
            ->withQueryString();

        $kerusakans = Kerusakan::orderBy('kode')->get();

        return view('admin.rekomendasi.index', compact('rekomendasis', 'kerusakans'));
    }

    public function create(Request $request)
    {
        $kerusakans = Kerusakan::orderBy('kode')->get();
        $terpilih   = $request->kerusakan_id;

        // Urutan berikutnya otomatis untuk kerusakan yang dipilih
        $urutanBaru = $terpilih
            ? (int) Rekomendasi::where('kerusakan_id', $terpilih)->max('urutan') + 1
            : 1;

        return view('admin.rekomendasi.create', compact('kerusakans', 'terpilih', 'urutanBaru'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kerusakan_id' => 'required|exists:kerusakans,id',
            'urutan'       => 'required|integer|min:1|max:99',
            'langkah'      => 'required|string',
        ]);

        Rekomendasi::create($data);

        return redirect()
            ->route('admin.rekomendasi.index', ['kerusakan_id' => $data['kerusakan_id']])
            ->with('sukses', 'Rekomendasi berhasil ditambahkan.');
    }

    public function edit(Rekomendasi $rekomendasi)
    {
        $kerusakans = Kerusakan::orderBy('kode')->get();

        return view('admin.rekomendasi.edit', compact('rekomendasi', 'kerusakans'));
    }

    public function update(Request $request, Rekomendasi $rekomendasi)
    {
        $data = $request->validate([
            'kerusakan_id' => 'required|exists:kerusakans,id',
            'urutan'       => 'required|integer|min:1|max:99',
            'langkah'      => 'required|string',
        ]);

        $rekomendasi->update($data);

        return redirect()
            ->route('admin.rekomendasi.index', ['kerusakan_id' => $data['kerusakan_id']])
            ->with('sukses', 'Rekomendasi berhasil diperbarui.');
    }

    public function destroy(Rekomendasi $rekomendasi)
    {
        $kerusakanId = $rekomendasi->kerusakan_id;
        $rekomendasi->delete();

        return redirect()
            ->route('admin.rekomendasi.index', ['kerusakan_id' => $kerusakanId])
            ->with('sukses', 'Rekomendasi berhasil dihapus.');
    }
}