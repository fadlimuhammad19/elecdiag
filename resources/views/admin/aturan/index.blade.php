@extends('layouts.app')
@section('title', 'Kelola Aturan')

@section('content')
    <div class="flex items-center justify-between mb-4">
       <p class="text-zinc-400">Kelola aturan gejala dan kerusakan</p>
        <a href="{{ route('admin.aturan.create') }}" class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">+ Tambah Aturan</a>
    </div>

    @if (session('sukses'))
        <div class="bg-green-500/10 text-green-400 p-3 rounded mb-4">{{ session('sukses') }}</div>
    @endif

    <form method="GET" class="mb-4">
        <select name="kerusakan_id" onchange="this.form.submit()" class="bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500">
            <option value="">Semua kerusakan</option>
            @foreach ($kerusakans as $k)
                <option value="{{ $k->id }}" @selected(request('kerusakan_id') == $k->id)>
                    {{ $k->kode }} - {{ $k->nama }}
                </option>
            @endforeach
        </select>
    </form>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
        <table class="w-full text-left text-sm">
            <thead class="bg-zinc-800/50 text-zinc-400">
                <tr>
                    <th class="p-3">Kerusakan (THEN)</th>
                    <th class="p-3">Gejala (IF)</th>
                    <th class="p-3">CF Pakar</th>
                    <th class="p-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($aturans as $a)
                    <tr class="border-t border-zinc-800">
                        <td class="p-3">
                            <span class="font-mono text-zinc-500">{{ $a->kerusakan->kode ?? '-' }}</span>
                            {{ $a->kerusakan->nama ?? '-' }}
                        </td>
                        <td class="p-3">
                            <span class="font-mono text-zinc-500">{{ $a->gejala->kode ?? '-' }}</span>
                            {{ $a->gejala->nama ?? '-' }}
                        </td>
                        <td class="p-3">{{ number_format($a->cf_pakar, 2) }}</td>
                        <td class="p-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.aturan.edit', $a) }}" class="text-violet-400 hover:underline">Edit</a>
                            <form action="{{ route('admin.aturan.destroy', $a) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Hapus aturan ini?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-400 hover:underline ml-3">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="p-4 text-center text-zinc-500">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $aturans->links() }}</div>
@endsection