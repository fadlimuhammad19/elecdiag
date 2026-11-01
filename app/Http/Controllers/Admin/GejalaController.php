<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gejala;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;

class GejalaController extends Controller
{
    public function index(Request $request)
    {
        $gejalas = Gejala::query()
            ->when($request->q, fn ($q, $cari) => $q->where('nama', 'like', "%{$cari}%")
                ->orWhere('kode', 'like', "%{$cari}%"))
            ->orderBy('kode')
            ->paginate(10)
            ->withQueryString();

        return view('admin.gejala.index', compact('gejalas'));
    }

    public function create()
    {
        return view('admin.gejala.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'       => 'required|string|max:10|unique:gejalas,kode',
            'nama'       => 'required|string|max:255',
            'pertanyaan' => 'nullable|string|max:255',
        ]);

        Gejala::create($data);

        return redirect()->route('admin.gejala.index')->with('sukses', 'Gejala berhasil ditambahkan.');
    }

    public function edit(Gejala $gejala)
    {
        return view('admin.gejala.edit', compact('gejala'));
    }

    public function update(Request $request, Gejala $gejala)
    {
        $data = $request->validate([
            'kode'       => ['required', 'string', 'max:10', ValidationRule::unique('gejalas', 'kode')->ignore($gejala->id)],
            'nama'       => 'required|string|max:255',
            'pertanyaan' => 'nullable|string|max:255',
        ]);

        $gejala->update($data);

        return redirect()->route('admin.gejala.index')->with('sukses', 'Gejala berhasil diperbarui.');
    }

    public function destroy(Gejala $gejala)
    {
        $gejala->delete();

        return redirect()->route('admin.gejala.index')->with('sukses', 'Gejala berhasil dihapus.');
    }
}