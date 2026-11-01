<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::withCount('komponens')->orderBy('kode')->paginate(10);

        return view('admin.kategori.index', compact('kategoris'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kode'      => 'required|string|max:10|unique:kategoris,kode',
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        Kategori::create($data);

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $data = $request->validate([
            'kode'      => ['required', 'string', 'max:10', ValidationRule::unique('kategoris', 'kode')->ignore($kategori->id)],
            'nama'      => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
        ]);

        $kategori->update($data);

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        if ($kategori->komponens()->exists()) {
            return back()->with('error', 'Kategori masih memiliki komponen. Pindahkan atau hapus komponennya dulu.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('sukses', 'Kategori berhasil dihapus.');
    }
}