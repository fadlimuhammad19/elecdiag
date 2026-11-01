@extends('layouts.app')
@section('title', 'Kelola Komponen')

@section('content')
    <div class="flex items-center justify-between mb-4">
       <p class="text-zinc-400">Kelola komponen dan perangkat</p>
        <a href="{{ route('admin.komponen.create') }}" class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">+ Tambah Komponen</a>
    </div>

    @if (session('sukses'))
        <div class="bg-green-500/10 text-green-400 p-3 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    <form method="GET" class="mb-4">
        <select name="kategori_id" onchange="this.form.submit()" class="bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">
            <option value="">Semua kategori</option>
            @foreach ($kategoris as $kat)
                <option value="{{ $kat->id }}" @selected(request('kategori_id') == $kat->id)>{{ $kat->nama }}</option>
            @endforeach
        </select>
    </form>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-800/50 text-zinc-400">
                <tr>
                    <th class="p-3">Kode</th>
                    <th class="p-3">Nama</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Arus</th>
                    <th class="p-3">Kerusakan</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($komponens as $c)
                    <tr class="border-t border-zinc-800">
                        <td class="p-3 font-mono">{{ $c->kode }}</td>
                        <td class="p-3">{{ $c->nama }}</td>
                        <td class="p-3">{{ $c->kategori->nama ?? '-' }}</td>
                        <td class="p-3">{{ $c->jenis_arus ?? '-' }}</td>
                        <td class="p-3">{{ $c->kerusakans_count }}</td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.komponen.edit', $c) }}" class="text-violet-400 hover:underline">Edit</a>
                            <form action="{{ route('admin.komponen.destroy', $c) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus komponen ini? Kerusakan, aturan, dan rekomendasinya ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-400 hover:underline ml-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="p-4 text-center text-zinc-500">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $komponens->links() }}</div>
@endsection