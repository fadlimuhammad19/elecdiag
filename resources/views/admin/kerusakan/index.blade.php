@extends('layouts.app')
@section('title', 'Kelola Kerusakan')

@section('content')
    @php
        $warna = ['Tinggi' => 'bg-red-500/10 text-red-400', 'Sedang' => 'bg-yellow-500/10 text-yellow-400', 'Rendah' => 'bg-green-500/10 text-green-400'];
    @endphp

    <div class="flex items-center justify-between mb-4">
       <p class="text-zinc-400">Kelola data kerusakan</p>
        <a href="{{ route('admin.kerusakan.create') }}" class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">+ Tambah Kerusakan</a>
    </div>

    @if (session('sukses'))
        <div class="bg-green-500/10 text-green-400 p-3 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    <form method="GET" class="mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama kerusakan..."
               class="bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500 w-full max-w-sm">
    </form>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-800/50 text-zinc-400">
                <tr>
                    <th class="p-3">Kode</th>
                    <th class="p-3">Nama Kerusakan</th>
                    <th class="p-3">Komponen</th>
                    <th class="p-3">Prioritas</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kerusakans as $k)
                    <tr class="border-t border-zinc-800">
                        <td class="p-3 font-mono">{{ $k->kode }}</td>
                        <td class="p-3">{{ $k->nama }}</td>
                        <td class="p-3">{{ $k->komponen->nama ?? '-' }}</td>
                        <td class="p-3">
                            <span class="text-xs px-2 py-1 rounded {{ $warna[$k->prioritas] ?? '' }}">{{ $k->prioritas }}</span>
                        </td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.kerusakan.edit', $k) }}" class="text-violet-400 hover:underline">Edit</a>
                            <form action="{{ route('admin.kerusakan.destroy', $k) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus kerusakan ini? Aturan dan rekomendasinya ikut terhapus.')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-400 hover:underline ml-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="p-4 text-center text-zinc-500">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $kerusakans->links() }}</div>
@endsection