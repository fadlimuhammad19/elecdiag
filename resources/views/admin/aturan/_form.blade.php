@if ($errors->any())
    <div class="bg-red-500/10 text-red-400 p-3 rounded mb-4 text-sm">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm mb-1">Kerusakan</label>
        <select name="kerusakan_id" required class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">
            <option value="">-- Pilih kerusakan --</option>
            @foreach ($kerusakans as $k)
                <option value="{{ $k->id }}" @selected(old('kerusakan_id', $aturan->kerusakan_id ?? '') == $k->id)>
                    {{ $k->kode }} - {{ $k->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm mb-1">Gejala</label>
        <select name="gejala_id" required class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">
            <option value="">-- Pilih gejala --</option>
            @foreach ($gejalas as $g)
                <option value="{{ $g->id }}" @selected(old('gejala_id', $aturan->gejala_id ?? '') == $g->id)>
                    {{ $g->kode }} - {{ $g->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm mb-1">CF pakar (0.01 - 1.00)</label>
        <input type="number" name="cf_pakar" step="0.01" min="0.01" max="1" required
               value="{{ old('cf_pakar', $aturan->cf_pakar ?? '') }}"
               class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500" placeholder="0.80">
        <p class="text-xs text-zinc-500 mt-1">Semakin besar, semakin kuat gejala ini menunjukkan kerusakan tersebut.</p>
    </div>
</div>

<div class="flex gap-3 mt-6">
    <button class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">Simpan</button>
    <a href="{{ route('admin.aturan.index') }}" class="px-4 py-2 border border-zinc-700 text-zinc-300 hover:bg-zinc-800 rounded-lg">Batal</a>
</div>