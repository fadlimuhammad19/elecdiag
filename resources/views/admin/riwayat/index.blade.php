@extends('layouts.app')
@section('title', 'Riwayat Semua')

@section('content')
    @php
        $input = 'bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-sm text-zinc-200 focus:outline-none focus:border-violet-500';
        $warna = [
            'Tinggi' => 'bg-red-500/10 text-red-400',
            'Sedang' => 'bg-yellow-500/10 text-yellow-400',
            'Rendah' => 'bg-green-500/10 text-green-400',
        ];
    @endphp

    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <p class="text-zinc-400">
            Total <span class="text-white font-medium">{{ $riwayat->total() }}</span> diagnosa dari semua pengguna.
        </p>
        <a href="{{ route('admin.riwayat.ekspor', request()->query()) }}"
           class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg text-sm">Ekspor CSV</a>
    </div>

    <form method="GET" class="grid sm:grid-cols-2 lg:grid-cols-6 gap-3 mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
               class="{{ $input }} lg:col-span-2">

        <select name="komponen_id" onchange="this.form.submit()" class="{{ $input }}">
            <option value="">Semua komponen</option>
            @foreach ($komponens as $c)
                <option value="{{ $c->id }}" @selected(request('komponen_id') == $c->id)>{{ $c->nama }}</option>
            @endforeach
        </select>

        <select name="prioritas" onchange="this.form.submit()" class="{{ $input }}">
            <option value="">Semua prioritas</option>
            @foreach (['Tinggi', 'Sedang', 'Rendah'] as $p)
                <option value="{{ $p }}" @selected(request('prioritas') === $p)>{{ $p }}</option>
            @endforeach
        </select>

        <input type="date" name="dari" value="{{ request('dari') }}" onchange="this.form.submit()" class="{{ $input }}" title="Dari tanggal">
        <input type="date" name="sampai" value="{{ request('sampai') }}" onchange="this.form.submit()" class="{{ $input }}" title="Sampai tanggal">

        <div class="lg:col-span-6 flex gap-3 text-sm">
            <button class="text-violet-400 hover:underline">Terapkan</button>
            <a href="{{ route('admin.riwayat.index') }}" class="text-zinc-400 hover:underline">Reset filter</a>
        </div>
    </form>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-zinc-500 text-xs uppercase">
                <tr>
                    <th class="pb-3 font-medium">Tanggal</th>
                    <th class="pb-3 font-medium">Pengguna</th>
                    <th class="pb-3 font-medium">Komponen</th>
                    <th class="pb-3 font-medium">Hasil</th>
                    <th class="pb-3 font-medium">Prioritas</th>
                    <th class="pb-3 font-medium">Keyakinan</th>
                    <th class="pb-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $d)
                    <tr class="border-t border-zinc-800">
                        <td class="py-3 text-zinc-400 whitespace-nowrap">{{ $d->created_at->format('d M Y, H:i') }}</td>
                        <td class="py-3">{{ $d->user->name ?? 'Tamu' }}</td>
                        <td class="py-3">{{ $d->kerusakan->komponen->nama ?? '-' }}</td>
                        <td class="py-3">{{ $d->kerusakan->nama ?? '-' }}</td>
                        <td class="py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $warna[$d->kerusakan->prioritas ?? ''] ?? '' }}">
                                {{ $d->kerusakan->prioritas ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 w-36">
                            <div class="flex items-center gap-2">
                                <div class="h-1.5 flex-1 bg-zinc-800 rounded-full">
                                    <div class="h-1.5 bg-violet-500 rounded-full" style="width: {{ $d->nilai_cf * 100 }}%"></div>
                                </div>
                                <span class="text-xs text-zinc-300">{{ number_format($d->nilai_cf * 100, 0) }}%</span>
                            </div>
                        </td>
                        <td class="py-3 text-right">
                            <a href="{{ route('diagnosa.hasil', $d) }}" class="text-violet-400 hover:underline">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-zinc-500">Tidak ada diagnosa yang cocok dengan filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $riwayat->links() }}</div>
@endsection