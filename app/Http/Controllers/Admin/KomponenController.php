<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Komponen;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;

class KomponenController extends Controller
{
    public function index(Request $request)
    {
        $komponens = Komponen::with('kategori')
            ->withCount('kerusakans')
            ->when($request->kategori_id, fn ($q, $id) => $q->where('kategori_id', $id))
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.komponen.index', compact('komponens', 'kategoris'));
    }

    public function create()
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.komponen.create', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'        => 'required|string|max:10|unique:komponens,kode',
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'jenis_arus'  => 'nullable|in:AC,DC,AC/DC',
            'deskripsi'   => 'nullable|string',
        ]);

        Komponen::create($data);

        return redirect()->route('admin.komponen.index')->with('sukses', 'Komponen berhasil ditambahkan.');
    }

    public function edit(Komponen $komponen)
    {
        $kategoris = Kategori::orderBy('nama')->get();

        return view('admin.komponen.edit', compact('komponen', 'kategoris'));
    }

    public function update(Request $request, Komponen $komponen)
    {
        $data = $request->validate([
            'kode'        => ['required', 'string', 'max:10', ValidationRule::unique('komponens', 'kode')->ignore($komponen->id)],
            'nama'        => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'jenis_arus'  => 'nullable|in:AC,DC,AC/DC',
            'deskripsi'   => 'nullable|string',
        ]);

        $komponen->update($data);

        return redirect()->route('admin.komponen.index')->with('sukses', 'Komponen berhasil diperbarui.');
    }

    public function destroy(Komponen $komponen)
    {
        $komponen->delete();

        return redirect()->route('admin.komponen.index')->with('sukses', 'Komponen berhasil dihapus.');
    }
}