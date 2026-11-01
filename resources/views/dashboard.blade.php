@extends('layouts.app')
@section('title', 'Beranda')

@section('content')
    @php
        $prioritas = [
            'Tinggi' => 'bg-red-500/10 text-red-400',
            'Sedang' => 'bg-yellow-500/10 text-yellow-400',
            'Rendah' => 'bg-green-500/10 text-green-400',
        ];
        $kartu = [
            ['Total Konsultasi',       $stat['total'],                   '',  'stethoscope',    true],
            ['Rata-rata Keyakinan',    (int) round($stat['rata'] * 100), '%', 'gauge',          false],
            ['Kasus Prioritas Tinggi', $stat['tinggi'],                  '',  'triangle-alert', false],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
        @foreach ($kartu as [$label, $nilai, $satuan, $ikon, $utama])
            <div class="rounded-2xl border border-white/10 p-5 {{ $utama ? 'bg-violet-500/10' : 'bg-white/5' }}">
                <p class="text-sm text-gray-400">{{ $label }}</p>
                <p class="mt-2 text-3xl font-semibold text-white">{{ $nilai }}{{ $satuan }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-8 rounded-2xl border border-white/10 bg-white/5 p-5">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Riwayat Terakhir</h2>
            <a href="{{ url('/riwayat') }}" class="text-sm text-violet-400 hover:underline">Lihat semua</a>
        </div>

        @forelse ($riwayatTerakhir as $d)
            <div class="flex items-center justify-between border-t border-white/10 py-3">
                <div>
                    <p class="font-medium text-white">{{ $d->kerusakan->nama ?? '-' }}</p>
                    <p class="text-sm text-gray-400">
                        {{ $d->kerusakan->komponen->nama ?? '-' }} · {{ $d->created_at->format('d M Y H:i') }}
                    </p>
                </div>
                <span class="rounded-full px-3 py-1 text-xs {{ $prioritas[$d->kerusakan->prioritas ?? ''] ?? '' }}">
                    {{ $d->kerusakan->prioritas ?? '-' }}
                </span>
            </div>
        @empty
            <p class="text-sm text-gray-400">Belum ada riwayat konsultasi.</p>
        @endforelse
    </div>
@endsection