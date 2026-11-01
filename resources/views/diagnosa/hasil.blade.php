@extends('layouts.app')
@section('title', 'Hasil Diagnosa')

@section('content')
    @php
        $k = $diagnosa->kerusakan;
        $persen = (int) round($diagnosa->nilai_cf * 100);
        $keliling = 339.29;
        $offset = round($keliling * (1 - $diagnosa->nilai_cf), 2);

        $langkah = $k->rekomendasis->isNotEmpty()
            ? $k->rekomendasis->map(fn ($r) => $r->urutan . '. ' . $r->langkah)->implode("\n")
            : $k->solusi;

        $ringkasan = "Hasil diagnosa ElecDiag\n"
            . "Komponen: {$k->komponen->nama}\n"
            . "Kemungkinan kerusakan: {$k->nama} ({$persen}%)\n"
            . "Prioritas: {$k->prioritas}\n"
            . "Penyebab: {$k->penyebab}\n"
            . "Penanganan:\n" . $langkah;
    @endphp

    <div class="grid lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-4">

            <div class="rounded-2xl p-6 border border-violet-400/40 bg-gradient-to-br from-violet-500 to-violet-800">
                <div class="flex items-center gap-6">
                    <div class="relative w-32 h-32 shrink-0" x-data="ring({{ $persen }}, {{ $offset }}, {{ $keliling }})">
                        <svg viewBox="0 0 120 120" class="w-32 h-32 -rotate-90">
                            <circle cx="60" cy="60" r="54" fill="none" stroke="rgba(255,255,255,.2)" stroke-width="10"/>
                            <circle cx="60" cy="60" r="54" fill="none" stroke="#fff" stroke-width="10"
                                    stroke-linecap="round" stroke-dasharray="{{ $keliling }}"
                                    style="stroke-dashoffset: {{ $keliling }}; transition: stroke-dashoffset 1.2s ease"
                                    :style="`stroke-dashoffset: ${mulai ? offset : keliling}; transition: stroke-dashoffset 1.2s ease`"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center text-2xl font-bold text-white">
                            <span x-text="angka">{{ $persen }}</span>%
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-violet-100">Kemungkinan kerusakan</p>
                        <h2 class="text-2xl font-bold text-white">{{ $k->nama }}</h2>
                        <p class="text-violet-100 text-sm mt-1">{{ $k->komponen->nama }}</p>
                        <span class="inline-block mt-3 text-xs px-2 py-1 rounded-full bg-white/20 text-white">
                            Prioritas: {{ $k->prioritas }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
                <h3 class="font-semibold text-white mb-3">Gejala yang dipilih</h3>
                <ul class="space-y-1 text-sm text-zinc-300">
                    @foreach ($diagnosa->gejalas as $g)
                        <li class="flex items-center gap-2">
                            <span class="w-5 h-5 rounded-full bg-green-500/15 text-green-400 flex items-center justify-center text-xs">✓</span>
                            {{ $g->nama }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
                <h3 class="font-semibold text-white mb-2">Penyebab potensial</h3>
                <p class="text-sm text-zinc-400">{{ $k->penyebab }}</p>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
                <h3 class="font-semibold text-white mb-3">Rekomendasi penanganan</h3>
                <ol class="space-y-3 text-sm text-zinc-300">
                    @forelse ($k->rekomendasis as $r)
                        <li class="flex gap-3">
                            <span class="w-6 h-6 shrink-0 rounded-full bg-violet-500/20 text-violet-300 flex items-center justify-center text-xs">
                                {{ $loop->iteration }}
                            </span>
                            <span>{{ $r->langkah }}</span>
                        </li>
                    @empty
                        <li class="text-zinc-400">{{ $k->solusi }}</li>
                    @endforelse
                </ol>
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5" x-data="{ siap: false }"
                 x-init="setTimeout(() => siap = true, 200)">
                <h3 class="font-semibold text-white mb-4">Semua kemungkinan</h3>
                <div class="space-y-4">
                    @foreach ($diagnosa->hasil_semua as $h)
                        <div>
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-zinc-300">{{ $h['nama'] }}</span>
                                <span class="text-zinc-400">{{ $h['persen'] }}%</span>
                            </div>
                            <div class="h-2 bg-zinc-800 rounded-full">
                                <div class="h-2 bg-violet-500 rounded-full transition-all duration-700"
                                     :style="{ width: siap ? '{{ $h['persen'] }}%' : '0%' }"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 space-y-2"
                 x-data="{
                     ok: false,
                     teks: @js($ringkasan),
                     async salin() {
                         try {
                             await navigator.clipboard.writeText(this.teks);
                             this.ok = true;
                             setTimeout(() => this.ok = false, 2000);
                         } catch (e) {}
                     }
                 }">
                <a href="{{ route('diagnosa.cetak', $diagnosa) }}" target="_blank"
                   class="block text-center bg-violet-500 hover:bg-violet-600 text-white py-2 rounded-lg">Cetak / Simpan PDF</a>
                <button type="button" x-on:click="salin()"
                        class="w-full border border-zinc-700 text-zinc-300 hover:bg-zinc-800 py-2 rounded-lg">
                    <span x-show="!ok">Salin ringkasan</span>
                    <span x-show="ok" x-cloak class="text-green-400">✓ Tersalin</span>
                </button>
                <a href="{{ route('diagnosa.index') }}"
                   class="block text-center border border-zinc-700 text-zinc-300 hover:bg-zinc-800 py-2 rounded-lg">Konsultasi Lagi</a>
                <a href="{{ route('riwayat') }}"
                   class="block text-center text-violet-400 hover:underline py-1">Lihat Riwayat</a>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function ring(target, offset, keliling) {
        return {
            mulai: false,
            angka: 0,
            offset: offset,
            keliling: keliling,
            init() {
                setTimeout(() => {
                    this.mulai = true;
                    const awal = performance.now();
                    const durasi = 1000;
                    const langkah = (t) => {
                        const p = Math.min((t - awal) / durasi, 1);
                        this.angka = Math.round(target * p);
                        if (p < 1) requestAnimationFrame(langkah);
                    };
                    requestAnimationFrame(langkah);
                }, 150);
            },
        };
    }
</script>
@endpush