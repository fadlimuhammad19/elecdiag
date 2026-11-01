@extends('layouts.app')
@section('title', 'Konsultasi')

@section('content')
    @php
        $daftar = $kategoris->flatMap(fn ($k) => $k->komponens->map(fn ($c) => [
            'nama'     => $c->nama,
            'kode'     => $c->kode,
            'arus'     => $c->jenis_arus,
            'kat'      => (string) $k->id,
            'kategori' => $k->nama,
            'url'      => route('diagnosa.wizard', $c),
        ]))->values();
    @endphp

    <div x-data="konsultasi(@js($daftar))">
        <p class="text-zinc-400 mb-4">Pilih komponen atau perangkat yang ingin diperiksa.</p>

        <div class="flex flex-col md:flex-row gap-3 mb-2">
            <input type="text" x-model="q" placeholder="Cari komponen, misalnya kapasitor atau laptop..."
                   class="flex-1 bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-zinc-200
                          focus:outline-none focus:border-violet-500">
        </div>

        <div class="flex flex-wrap gap-2 mb-2">
            <button type="button" x-on:click="kat = 'semua'"
                    :class="kat === 'semua' ? 'bg-violet-500 text-white' : 'bg-zinc-900 text-zinc-400 border border-zinc-800'"
                    class="px-3 py-1.5 rounded-full text-sm transition">Semua</button>
            @foreach ($kategoris as $k)
                <button type="button" x-on:click="kat = '{{ $k->id }}'"
                        :class="kat === '{{ $k->id }}' ? 'bg-violet-500 text-white' : 'bg-zinc-900 text-zinc-400 border border-zinc-800'"
                        class="px-3 py-1.5 rounded-full text-sm transition">{{ $k->nama }}</button>
            @endforeach
        </div>

        <p class="text-xs text-zinc-500 mb-4">
            Menampilkan <span x-text="tampil.length"></span> dari <span x-text="daftar.length"></span> komponen
        </p>

        <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <template x-for="c in tampil" :key="c.url">
                <a :href="c.url"
                   class="group rounded-2xl p-5 border border-zinc-800 bg-zinc-900 hover:border-violet-500 hover:-translate-y-0.5 transition">
                    <div class="w-10 h-10 rounded-full bg-zinc-800 group-hover:bg-violet-500 flex items-center justify-center
                                font-semibold text-violet-300 group-hover:text-white mb-5 transition"
                         x-text="c.nama.charAt(0)"></div>
                    <p class="font-semibold text-white" x-text="c.nama"></p>
                    <p class="text-xs text-zinc-500 mt-1" x-text="c.kategori + (c.arus ? ' · ' + c.arus : '')"></p>
                </a>
            </template>
        </div>

        <div x-show="tampil.length === 0" x-cloak class="text-center py-16 text-zinc-500">
            Tidak ada komponen yang cocok dengan pencarian kamu.
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function konsultasi(daftar) {
        return {
            q: '',
            kat: 'semua',
            daftar: daftar,
            get tampil() {
                const k = this.q.toLowerCase();
                return this.daftar.filter(c =>
                    (this.kat === 'semua' || c.kat === this.kat) &&
                    (c.nama.toLowerCase().includes(k) || c.kode.toLowerCase().includes(k) || c.kategori.toLowerCase().includes(k))
                );
            },
        };
    }
</script>
@endpush