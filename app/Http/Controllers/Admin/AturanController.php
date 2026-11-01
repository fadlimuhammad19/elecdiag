<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Rule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule as ValidationRule;

class AturanController extends Controller
{
    public function index(Request $request)
    {
        $aturans = Rule::with('kerusakan', 'gejala')
            ->when($request->kerusakan_id, fn ($q, $id) => $q->where('kerusakan_id', $id))
            ->orderBy('kerusakan_id')
            ->orderBy('gejala_id')
            ->paginate(15)
            ->withQueryString();

        $kerusakans = Kerusakan::orderBy('kode')->get();

        return view('admin.aturan.index', compact('aturans', 'kerusakans'));
    }

    public function create()
    {
        $kerusakans = Kerusakan::orderBy('kode')->get();
        $gejalas    = Gejala::orderBy('kode')->get();

        return view('admin.aturan.create', compact('kerusakans', 'gejalas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'kerusakan_id' => 'required|exists:kerusakans,id',
            'gejala_id'    => [
                'required',
                'exists:gejalas,id',
                ValidationRule::unique('rules')->where('kerusakan_id', $request->kerusakan_id),
            ],
            'cf_pakar'     => 'required|numeric|min:0.01|max:1',
        ], [
            'gejala_id.unique' => 'Aturan untuk kerusakan dan gejala ini sudah ada.',
        ]);

        Rule::create($data);

        return redirect()->route('admin.aturan.index')->with('sukses', 'Aturan berhasil ditambahkan.');
    }

    public function edit(Rule $aturan)
    {
        $kerusakans = Kerusakan::orderBy('kode')->get();
        $gejalas    = Gejala::orderBy('kode')->get();

        return view('admin.aturan.edit', compact('aturan', 'kerusakans', 'gejalas'));
    }

    public function update(Request $request, Rule $aturan)
    {
        $data = $request->validate([
            'kerusakan_id' => 'required|exists:kerusakans,id',
            'gejala_id'    => [
                'required',
                'exists:gejalas,id',
                ValidationRule::unique('rules')
                    ->where('kerusakan_id', $request->kerusakan_id)
                    ->ignore($aturan->id),
            ],
            'cf_pakar'     => 'required|numeric|min:0.01|max:1',
        ], [
            'gejala_id.unique' => 'Aturan untuk kerusakan dan gejala ini sudah ada.',
        ]);

        $aturan->update($data);

        return redirect()->route('admin.aturan.index')->with('sukses', 'Aturan berhasil diperbarui.');
    }

    public function destroy(Rule $aturan)
    {
        $aturan->delete();

        return redirect()->route('admin.aturan.index')->with('sukses', 'Aturan berhasil dihapus.');
    }
}