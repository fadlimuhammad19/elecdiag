<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kerusakan;
use App\Models\Komponen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;

class KerusakanController extends Controller
{
    public function index(Request $request)
    {
        $kerusakans = Kerusakan::with('komponen')
            ->when($request->q, function ($query, $cari) {
                $query->where(function ($w) use ($cari) {
                    $w->where('nama', 'like', "%{$cari}%")
                      ->orWhere('kode', 'like', "%{$cari}%");
                });
            })
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        return view('admin.kerusakan.index', compact('kerusakans'));
    }

    public function create()
    {
        $komponens = Komponen::orderBy('nama')->get();

        return view('admin.kerusakan.create', compact('komponens'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'        => 'required|string|max:10|unique:kerusakans,kode',
            'nama'        => 'required|string|max:255',
            'komponen_id' => 'required|exists:komponens,id',
            'prioritas'   => 'required|in:Rendah,Sedang,Tinggi',
            'penyebab'    => 'nullable|string',
            'solusi'      => 'nullable|string',
        ]);

        Kerusakan::create($data);

        return redirect()->route('admin.kerusakan.index')->with('sukses', 'Kerusakan berhasil ditambahkan.');
    }

    public function edit(Kerusakan $kerusakan)
    {
        $komponens = Komponen::orderBy('nama')->get();

        return view('admin.kerusakan.edit', compact('kerusakan', 'komponens'));
    }

    public function update(Request $request, Kerusakan $kerusakan)
    {
        $data = $request->validate([
            'kode'        => ['required', 'string', 'max:10', ValidationRule::unique('kerusakans', 'kode')->ignore($kerusakan->id)],
            'nama'        => 'required|string|max:255',
            'komponen_id' => 'required|exists:komponens,id',
            'prioritas'   => 'required|in:Rendah,Sedang,Tinggi',
            'penyebab'    => 'nullable|string',
            'solusi'      => 'nullable|string',
        ]);

        $kerusakan->update($data);

        return redirect()->route('admin.kerusakan.index')->with('sukses', 'Kerusakan berhasil diperbarui.');
    }

    public function destroy(Kerusakan $kerusakan)
    {
        $kerusakan->delete();

        return redirect()->route('admin.kerusakan.index')->with('sukses', 'Kerusakan berhasil dihapus.');
    }
}