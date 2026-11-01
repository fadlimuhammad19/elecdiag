@extends('layouts.app')
@section('title', 'Rekomendasi')

@section('content')
    <div class="flex items-center justify-between mb-4">
        <p class="text-zinc-400">Langkah penanganan untuk setiap kerusakan.</p>
        <a href="{{ route('admin.rekomendasi.create', ['kerusakan_id' => request('kerusakan_id')]) }}"
           class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg text-sm">+ Tambah Rekomendasi</a>
    </div>

    @if (session('sukses'))
        <div class="bg-green-500/10 text-green-400 p-3 rounded-lg mb-4 text-sm">{{ session('sukses') }}</div>
    @endif

    <form method="GET" class="mb-4">
        <select name="kerusakan_id" onchange="this.form.submit()"
                class="bg-zinc-900 border border-zinc-700 rounded-lg px-3 py-2 text-sm text-zinc-200">
            <option value="">Semua kerusakan</option>
            @foreach ($kerusakans as $k)
                <option value="{{ $k->id }}" @selected(request('kerusakan_id') == $k->id)>
                    {{ $k->kode }} - {{ $k->nama }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
        <table class="w-full text-left text-sm">
            <thead class="text-zinc-500 text-xs uppercase">
                <tr>
                    <th class="pb-3 font-medium">Kerusakan</th>
                    <th class="pb-3 font-medium">No</th>
                    <th class="pb-3 font-medium">Langkah</th>
                    <th class="pb-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekomendasis as $r)
                    <tr class="border-t border-zinc-800 align-top">
                        <td class="py-3 whitespace-nowrap">
                            <span class="font-mono text-zinc-500">{{ $r->kerusakan->kode ?? '-' }}</span>
                            {{ $r->kerusakan->nama ?? '-' }}
                        </td>
                        <td class="py-3">
                            <span class="w-6 h-6 rounded-full bg-violet-500/20 text-violet-300 inline-flex items-center justify-center text-xs">
                                {{ $r->urutan }}
                            </span>
                        </td>
                        <td class="py-3 text-zinc-300">{{ $r->langkah }}</td>
                        <td class="py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.rekomendasi.edit', $r) }}" class="text-violet-400 hover:underline">Edit</a>
                            <form action="{{ route('admin.rekomendasi.destroy', $r) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus langkah ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-400 hover:underline ml-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-6 text-center text-zinc-500">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $rekomendasis->links() }}</div>
@endsection