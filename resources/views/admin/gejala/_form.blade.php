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
        <input type="text" name="kode" value="{{ old('kode', $gejala->kode ?? '') }}" required
               class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500" placeholder="G30">
    </div>
    <div>
        <label class="block text-sm mb-1">Nama gejala</label>
        <input type="text" name="nama" value="{{ old('nama', $gejala->nama ?? '') }}" required
               class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">
    </div>
    <div>
        <label class="block text-sm mb-1">Pertanyaan untuk pengguna</label>
        <input type="text" name="pertanyaan" value="{{ old('pertanyaan', $gejala->pertanyaan ?? '') }}"
               class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500" placeholder="Apakah ...?">
    </div>
</div>

<div class="flex gap-3 mt-6">
    <button class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">Simpan</button>
    <a href="{{ route('admin.gejala.index') }}" class="px-4 py-2 border border-zinc-700 text-zinc-300 hover:bg-zinc-800 rounded-lg">Batal</a>
</div>