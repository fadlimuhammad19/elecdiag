@extends('layouts.app')
@section('title', 'Riwayat Konsultasi')

@section('content')
    @php
        $prioritas = [
            'Tinggi' => 'bg-red-500/10 text-red-400',
            'Sedang' => 'bg-yellow-500/10 text-yellow-400',
            'Rendah' => 'bg-green-500/10 text-green-400',
        ];
    @endphp

    <p class="text-zinc-400 mb-6">Semua diagnosa yang pernah Anda lakukan.</p>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
        <table class="w-full text-left text-sm">
            <thead class="text-zinc-500 text-xs uppercase">
                <tr>
                    <th class="pb-3 font-medium">No</th>
                    <th class="pb-3 font-medium">Tanggal</th>
                    <th class="pb-3 font-medium">Komponen</th>
                    <th class="pb-3 font-medium">Hasil Diagnosis</th>
                    <th class="pb-3 font-medium">Prioritas</th>
                    <th class="pb-3 font-medium text-right">Keyakinan</th>
                    <th class="pb-3"></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($riwayat as $d)
                    <tr class="border-t border-zinc-800">
                        <td class="py-3 text-zinc-500">{{ $riwayat->firstItem() + $loop->index }}</td>
                        <td class="py-3 text-zinc-400">{{ $d->created_at->format('d M Y') }}</td>
                        <td class="py-3">{{ $d->kerusakan->komponen->nama ?? '-' }}</td>
                        <td class="py-3">{{ $d->kerusakan->nama ?? '-' }}</td>
                        <td class="py-3">
                            <span class="text-xs px-2 py-1 rounded-full {{ $prioritas[$d->kerusakan->prioritas ?? ''] ?? '' }}">
                                {{ $d->kerusakan->prioritas ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 text-right font-medium text-white">{{ number_format($d->nilai_cf * 100, 0) }}%</td>
                        <td class="py-3 text-right">
                            <a href="{{ route('diagnosa.hasil', $d) }}" class="text-violet-400 hover:underline">Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-6 text-center text-zinc-500">Belum ada riwayat.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $riwayat->links() }}</div>
@endsection