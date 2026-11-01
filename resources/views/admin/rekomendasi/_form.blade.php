@php
    $input = 'w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500';
@endphp

@if ($errors->any())
    <div class="bg-red-500/10 text-red-400 p-3 rounded-lg mb-4 text-sm space-y-1">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="space-y-4">
    <div>
        <label class="block text-sm text-zinc-300 mb-1">Kerusakan</label>
        <select name="kerusakan_id" required class="{{ $input }}">
            <option value="">-- Pilih kerusakan --</option>
            @foreach ($kerusakans as $k)
                <option value="{{ $k->id }}"
                    @selected(old('kerusakan_id', $rekomendasi->kerusakan_id ?? $terpilih ?? '') == $k->id)>
                    {{ $k->kode }} - {{ $k->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="block text-sm text-zinc-300 mb-1">Nomor urut langkah</label>
        <input type="number" name="urutan" min="1" max="99" required
               value="{{ old('urutan', $rekomendasi->urutan ?? $urutanBaru ?? 1) }}" class="{{ $input }}">
    </div>
    <div>
        <label class="block text-sm text-zinc-300 mb-1">Langkah penanganan</label>
        <textarea name="langkah" rows="4" required class="{{ $input }}"
                  placeholder="Contoh: Matikan daya, lalu lepas komponen dari rangkaian.">{{ old('langkah', $rekomendasi->langkah ?? '') }}</textarea>
    </div>
</div>

<div class="flex gap-3 mt-6">
    <button class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">Simpan</button>
    <a href="{{ route('admin.rekomendasi.index') }}" class="px-4 py-2 border border-zinc-700 text-zinc-300 hover:bg-zinc-800 rounded-lg">Batal</a>
</div>