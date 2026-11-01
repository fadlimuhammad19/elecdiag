@if ($errors->any())
    <div class="bg-red-500/10 text-red-400 p-3 rounded mb-4 text-sm">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm mb-1">Kode</label>
        <input type="text" name="kode" value="{{ old('kode', $kerusakan->kode ?? '') }}" required
               class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500" placeholder="K11">
    </div>
    <div>
        <label class="block text-sm mb-1">Nama kerusakan</label>
        <input type="text" name="nama" value="{{ old('nama', $kerusakan->nama ?? '') }}" required
               class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">
    </div>
    <div>
        <label class="block text-sm mb-1">Komponen</label>
        <select name="komponen_id" required class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">
            <option value="">-- Pilih komponen --</option>
            @foreach ($komponens as $komponen)
                <option value="{{ $komponen->id }}"
                    @selected(old('komponen_id', $kerusakan->komponen_id ?? '') == $komponen->id)>
                    {{ $komponen->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm mb-1">Prioritas</label>
        <select name="prioritas" required class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">
            @foreach (['Rendah', 'Sedang', 'Tinggi'] as $p)
                <option value="{{ $p }}" @selected(old('prioritas', $kerusakan->prioritas ?? 'Sedang') === $p)>{{ $p }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm mb-1">Penyebab</label>
        <textarea name="penyebab" rows="3" class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">{{ old('penyebab', $kerusakan->penyebab ?? '') }}</textarea>
    </div>
    <div>
        <label class="block text-sm mb-1">Solusi ringkas</label>
        <textarea name="solusi" rows="3" class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">{{ old('solusi', $kerusakan->solusi ?? '') }}</textarea>
    </div>
</div>

<div class="flex gap-3 mt-6">
    <button class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">Simpan</button>
    <a href="{{ route('admin.kerusakan.index') }}" class="px-4 py-2 border border-zinc-700 text-zinc-300 hover:bg-zinc-800 rounded-lg">Batal</a>
</div>