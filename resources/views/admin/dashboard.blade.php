@extends('layouts.app')
@section('title', 'Dashboard Admin')

@section('content')
    @php
        $kartu = [
            ['Total Diagnosa',  $stat['diagnosa'],  'clipboard-check', true],
            ['Total Gejala',    $stat['gejala'],    'activity',        false],
            ['Total Kerusakan', $stat['kerusakan'], 'wrench',          false],
            ['Total Aturan',    $stat['aturan'],    'git-branch',      false],
            ['Total Pengguna',  $stat['pengguna'],  'users',           false],
        ];
    @endphp

    {{-- Kartu statistik --}}
    <div class="grid sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-4">
        @foreach ($kartu as [$label, $nilai, $icon, $utama])
            <div class="rounded-2xl p-5 border {{ $utama ? 'bg-gradient-to-br from-violet-500 to-violet-800 border-violet-400/40' : 'bg-zinc-900 border-zinc-800' }}">
                <div class="w-9 h-9 rounded-full flex items-center justify-center mb-4 {{ $utama ? 'bg-white/20' : 'bg-zinc-800' }}">
                    <i data-lucide="{{ $icon }}" class="w-4 h-4 {{ $utama ? 'text-white' : 'text-violet-400' }}"></i>
                </div>
                <p class="text-sm {{ $utama ? 'text-violet-100' : 'text-zinc-400' }}">{{ $label }}</p>
                <p class="text-3xl font-bold text-white mt-1" data-hitung="{{ $nilai }}">{{ $nilai }}</p>
            </div>
        @endforeach
    </div>

    {{-- Grafik --}}
    <div class="grid lg:grid-cols-3 gap-4 mb-4">
        <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-white">Diagnosa per Hari</h2>
                <div class="flex gap-1">
                    @foreach ([7, 14, 30] as $h)
                        <a href="{{ route('admin.dashboard', ['hari' => $h]) }}"
                           class="px-3 py-1 rounded-full text-xs transition
                                  {{ $hari === $h ? 'bg-violet-500 text-white' : 'bg-zinc-800 text-zinc-400 hover:text-white' }}">
                            {{ $h }} hari
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="h-64"><canvas id="grafik"></canvas></div>
        </div>

        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
            <h2 class="font-semibold text-white mb-4">Sebaran Prioritas</h2>
            @if (array_sum($donat->all()) > 0)
                <div class="h-64"><canvas id="donat"></canvas></div>
            @else
                <p class="text-sm text-zinc-500 py-16 text-center">Belum ada data diagnosa.</p>
            @endif
        </div>
    </div>

    {{-- Komponen terbanyak + diagnosa terbaru --}}
    <div class="grid lg:grid-cols-3 gap-4">
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
            <h2 class="font-semibold text-white mb-4">Komponen Terbanyak</h2>
            <div class="space-y-4" x-data="{ siap: false }" x-init="setTimeout(() => siap = true, 200)">
                @forelse ($topKomponen as $k)
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-zinc-300">{{ $k->nama }}</span>
                            <span class="text-zinc-400">{{ $k->total }}x</span>
                        </div>
                        <div class="h-2 bg-zinc-800 rounded-full">
                            <div class="h-2 bg-violet-500 rounded-full transition-all duration-700"
                                 :style="{ width: siap ? '{{ ($k->total / $maks) * 100 }}%' : '0%' }"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-zinc-500">Belum ada diagnosa.</p>
                @endforelse
            </div>
        </div>

        <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-5 overflow-x-auto">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-white">Diagnosa Terbaru</h2>
                <a href="{{ route('admin.riwayat.index') }}" class="text-sm text-violet-400 hover:underline">Lihat semua</a>
            </div>
            <table class="w-full text-left text-sm">
                <thead class="text-zinc-500 text-xs uppercase">
                    <tr>
                        <th class="pb-3 font-medium">Waktu</th>
                        <th class="pb-3 font-medium">Pengguna</th>
                        <th class="pb-3 font-medium">Hasil</th>
                        <th class="pb-3 font-medium text-right">Keyakinan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($terbaru as $d)
                        <tr class="border-t border-zinc-800">
                            <td class="py-3 text-zinc-400 whitespace-nowrap">{{ $d->created_at->format('d M, H:i') }}</td>
                            <td class="py-3">{{ $d->user->name ?? 'Tamu' }}</td>
                            <td class="py-3">{{ $d->kerusakan->nama ?? '-' }}</td>
                            <td class="py-3 text-right font-medium text-white">{{ number_format($d->nilai_cf * 100, 0) }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-6 text-center text-zinc-500">Belum ada diagnosa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Angka naik-hitung
    document.querySelectorAll('[data-hitung]').forEach(el => {
        const target = parseInt(el.dataset.hitung, 10) || 0;
        const awal = performance.now();
        const durasi = 900;
        const langkah = (t) => {
            const p = Math.min((t - awal) / durasi, 1);
            el.textContent = Math.round(target * p);
            if (p < 1) requestAnimationFrame(langkah);
        };
        requestAnimationFrame(langkah);
    });

    // Grafik garis
    const ctx = document.getElementById('grafik').getContext('2d');
    const grad = ctx.createLinearGradient(0, 0, 0, 260);
    grad.addColorStop(0, 'rgba(139,92,246,.5)');
    grad.addColorStop(1, 'rgba(139,92,246,0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($grafik['label']),
            datasets: [{
                label: 'Diagnosa',
                data: @json($grafik['data']),
                borderColor: '#8b5cf6',
                backgroundColor: grad,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointBackgroundColor: '#8b5cf6',
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#71717a', maxTicksLimit: 10 } },
                y: { beginAtZero: true, grid: { color: '#27272a' }, ticks: { color: '#71717a', precision: 0 } }
            }
        }
    });

    // Grafik donat
    const donat = document.getElementById('donat');
    if (donat) {
        new Chart(donat, {
            type: 'doughnut',
            data: {
                labels: ['Tinggi', 'Sedang', 'Rendah'],
                datasets: [{
                    data: @json($donat),
                    backgroundColor: ['#f87171', '#facc15', '#4ade80'],
                    borderWidth: 0,
                }]
            },
            options: {
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { legend: { position: 'bottom', labels: { color: '#a1a1aa' } } }
            }
        });
    }
</script>
@endpush