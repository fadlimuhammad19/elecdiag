@extends('layouts.app')
@section('title', 'Kelola Kategori')

@section('content')
    <div class="flex items-center justify-between mb-4">
       <p class="text-zinc-400">Kelola kategori perangkat</p>
        <a href="{{ route('admin.kategori.create') }}" class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">+ Tambah Kategori</a>
    </div>

    @if (session('sukses'))
        <div class="bg-green-500/10 text-green-400 p-3 rounded mb-4">{{ session('sukses') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-500/10 text-red-400 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-800/50 text-zinc-400">
                <tr>
                    <th class="p-3">Kode</th>
                    <th class="p-3">Nama Kategori</th>
                    <th class="p-3">Deskripsi</th>
                    <th class="p-3">Komponen</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($kategoris as $k)
                    <tr class="border-t border-zinc-800">
                        <td class="p-3 font-mono">{{ $k->kode }}</td>
                        <td class="p-3">{{ $k->nama }}</td>
                        <td class="p-3 text-zinc-500">{{ $k->deskripsi }}</td>
                        <td class="p-3">{{ $k->komponens_count }}</td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.kategori.edit', $k) }}" class="text-violet-400 hover:underline">Edit</a>
                            <form action="{{ route('admin.kategori.destroy', $k) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus kategori ini?')">
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

    <div class="mt-4">{{ $kategoris->links() }}</div>
@endsection