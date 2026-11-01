@extends('layouts.app')
@section('title', 'Konsultasi ' . $komponen->nama)

@section('content')
       @php
        $fileModel    = public_path('model/komponen/model.json');
        $fileMetadata = public_path('model/komponen/metadata.json');
        $modelAda = file_exists($fileModel) && filesize($fileModel) > 0
                 && file_exists($fileMetadata) && filesize($fileMetadata) > 0;
    @endphp

    @if (session('error'))
        <div class="bg-red-500/10 text-red-400 p-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
    @endif

    @if ($gejalas->isEmpty())
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 max-w-2xl">
            <p class="text-zinc-300">Komponen ini belum punya data gejala.</p>
            <a href="{{ route('diagnosa.index') }}" class="inline-block mt-4 text-violet-400 hover:underline">← Pilih komponen lain</a>
        </div>
    @else
        <div class="max-w-2xl" x-data="wizard()" x-on:keydown.window="tekan($event)">

            {{-- Analisis dari foto --}}
            @if ($modelAda)
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 mb-6">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="max-w-md">
                            <h3 class="font-semibold text-white">
                                Analisis dari foto
                                <span class="ml-1 text-xs px-2 py-0.5 rounded-full bg-violet-500/20 text-violet-300">Beta</span>
                            </h3>
                            <p class="text-sm text-zinc-400 mt-1">
                                Opsional. Foto komponen yang rusak, lalu gejala yang terlihat terisi otomatis.
                                Foto diproses di browser dan tidak dikirim ke server.
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <label class="cursor-pointer px-4 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white text-sm">
                                Ambil foto
                                <input type="file" accept="image/*" capture="environment" class="hidden"
                                       x-on:change="analisisFoto($event)">
                            </label>
                            <label class="cursor-pointer px-4 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:bg-zinc-800 text-sm">
                                Pilih file
                                <input type="file" accept="image/*" class="hidden" x-on:change="analisisFoto($event)">
                            </label>
                        </div>
                    </div>

                    <div x-show="fotoUrl" x-cloak class="mt-4 flex gap-4 items-start">
                        <img :src="fotoUrl" alt="Pratinjau foto"
                             class="w-28 h-28 object-cover rounded-xl border border-zinc-700 shrink-0">

                        <div class="flex-1 text-sm">
                            <p x-show="analisis.status === 'memuat'" class="text-zinc-400" x-text="analisis.pesan"></p>
                            <p x-show="analisis.status === 'error'" x-cloak class="text-red-400" x-text="analisis.pesan"></p>

                            <div x-show="analisis.status === 'selesai'" x-cloak>
                                <p class="text-zinc-200 mb-3" x-text="analisis.pesan"></p>

                                <div class="space-y-2 mb-3">
                                    <template x-for="s in analisis.semua" :key="s.nama">
                                        <div>
                                            <div class="flex justify-between text-xs mb-1">
                                                <span class="text-zinc-300" x-text="s.nama"></span>
                                                <span class="text-zinc-500" x-text="s.persen + '%'"></span>
                                            </div>
                                            <div class="h-1.5 bg-zinc-800 rounded-full">
                                                <div class="h-1.5 bg-violet-500 rounded-full transition-all duration-500"
                                                     :style="`width: ${s.persen}%`"></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" x-show="analisis.terisi.length" x-on:click="i = total"
                                        class="text-violet-400 hover:underline">
                                    Lihat jawaban yang terisi →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('diagnosa.proses') }}" method="POST">
                @csrf
                <input type="hidden" name="komponen_id" value="{{ $komponen->id }}">

                @foreach ($gejalas as $g)
                    <input type="hidden" name="jawaban[{{ $g->id }}]" :value="jawab[{{ $g->id }}] || 'tidak'">
                @endforeach

                {{-- Progres --}}
                <div class="flex justify-between text-sm text-zinc-400 mb-2">
                    <span x-show="!selesai">Pertanyaan <span x-text="i + 1"></span> dari <span x-text="total"></span></span>
                    <span x-show="selesai" x-cloak>Tinjau jawaban</span>
                    <span x-text="Math.round((Math.min(i, total) / total) * 100) + '%'"></span>
                </div>
                <div class="h-2 bg-zinc-800 rounded-full mb-6">
                    <div class="h-2 bg-violet-500 rounded-full transition-all duration-300"
                         :style="`width: ${(Math.min(i, total) / total) * 100}%`"></div>
                </div>

                {{-- Kartu pertanyaan --}}
                @foreach ($gejalas as $idx => $g)
                    <div x-show="i === {{ $idx }}" {{ $idx > 0 ? 'x-cloak' : '' }}
                         x-transition:enter="transition duration-200"
                         x-transition:enter-start="opacity-0 translate-x-4"
                         x-transition:enter-end="opacity-100 translate-x-0"
                         class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                        <p class="text-xs text-zinc-500 mb-2">{{ $g->kode }}</p>
                        <h2 class="text-lg font-semibold text-white mb-6">{{ $g->pertanyaan ?? $g->nama }}</h2>

                        <div class="grid sm:grid-cols-3 gap-3">
                            @foreach ([['ya', 'Ya', '1'], ['tidak', 'Tidak', '2'], ['tahu', 'Tidak tahu', '3']] as [$nilai, $label, $kunci])
                                <button type="button" x-on:click="pilih('{{ $nilai }}')"
                                        :class="jawab[{{ $g->id }}] === '{{ $nilai }}'
                                            ? 'border-violet-500 bg-violet-500/15 text-white'
                                            : 'border-zinc-800 text-zinc-300 hover:bg-zinc-800'"
                                        class="border rounded-xl px-4 py-4 text-left transition">
                                    <span class="block font-medium">{{ $label }}</span>
                                    <span class="hidden sm:block text-xs text-zinc-500 mt-1">tekan {{ $kunci }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Tinjau jawaban --}}
                <div x-show="selesai" x-cloak
                     x-transition:enter="transition duration-200"
                     x-transition:enter-start="opacity-0 translate-x-4"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                    <h2 class="text-lg font-semibold text-white mb-1">Semua pertanyaan sudah dijawab</h2>
                    <p class="text-sm text-zinc-400 mb-4">
                        Gejala yang kamu jawab <span class="text-violet-300">Ya</span>
                        (<span x-text="yaList.length"></span>):
                    </p>

                    <ul class="space-y-2 mb-4">
                        <template x-for="id in yaList" :key="id">
                            <li class="flex items-center gap-2 text-sm text-zinc-300">
                                <span class="w-5 h-5 rounded-full bg-green-500/15 text-green-400 flex items-center justify-center text-xs">✓</span>
                                <span x-text="nama[id]"></span>
                            </li>
                        </template>
                    </ul>

                    <p x-show="yaList.length === 0" class="text-sm text-yellow-400 bg-yellow-500/10 p-3 rounded-lg mb-4">
                        Belum ada gejala yang dijawab "Ya". Kembali dan jawab minimal satu gejala.
                    </p>
                </div>

                {{-- Tombol --}}
                <div class="flex justify-between mt-6">
                    <button type="button" x-on:click="mundur()" x-show="i > 0" x-cloak
                            class="px-4 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:bg-zinc-800">← Sebelumnya</button>
                    <span x-show="i === 0"></span>

                    <div>
                        <button type="button" x-on:click="i++" x-show="!selesai"
                                class="px-4 py-2 rounded-lg border border-zinc-700 text-zinc-300 hover:bg-zinc-800">Lewati →</button>

                        <button type="submit" x-show="selesai" x-cloak :disabled="yaList.length === 0"
                                class="px-5 py-2 rounded-lg bg-violet-500 hover:bg-violet-600 text-white disabled:opacity-40 disabled:cursor-not-allowed">
                            Lihat Hasil
                        </button>
                    </div>
                </div>

                <p class="text-xs text-zinc-600 mt-6 hidden sm:block">
                    Pintasan: 1 = Ya, 2 = Tidak, 3 = Tidak tahu, ← = kembali
                </p>
            </form>
        </div>
    @endif
@endsection

@push('scripts')
@php
    $cfgFoto = [
        'model'    => '/model/komponen/model.json',
        'metadata' => '/model/komponen/metadata.json',
        'ambang'   => config('analisis_foto.ambang', 0.7),
        'pemetaan' => config('analisis_foto.pemetaan', []),
    ];
@endphp
<script>
    window.DATA_WIZARD = {
        ids: @json($gejalas->pluck('id')->values()),
        nama: @json($gejalas->pluck('nama', 'id')),
        kodeKeId: @json($gejalas->pluck('id', 'kode')),
        foto: @json($cfgFoto),
    };

    let modelTM = null;

    function muatSkrip(src) {
        return new Promise((ok, gagal) => {
            const s = document.createElement('script');
            s.src = src;
            s.onload = ok;
            s.onerror = () => gagal(new Error('Gagal memuat ' + src));
            document.head.appendChild(s);
        });
    }

    // Pustaka dan model hanya dimuat saat pertama kali dibutuhkan
    function siapkanModel(urlModel, urlMetadata) {
        if (!modelTM) {
            modelTM = (async () => {
                await muatSkrip('https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@1.3.1/dist/tf.min.js');
                await muatSkrip('https://cdn.jsdelivr.net/npm/@teachablemachine/image@0.8/dist/teachablemachine-image.min.js');
                return await tmImage.load(urlModel, urlMetadata);
            })();
            modelTM.catch(() => { modelTM = null; }); // izinkan coba lagi kalau gagal
        }
        return modelTM;
    }

    function wizard() {
        const d = window.DATA_WIZARD;

        return {
            i: 0,
            ids: d.ids,
            nama: d.nama,
            kodeKeId: d.kodeKeId,
            foto: d.foto,
            jawab: {},
            timer: null,
            fotoUrl: null,
            analisis: { status: 'diam', pesan: '', semua: [], terisi: [] },

            get total() { return this.ids.length; },
            get selesai() { return this.i >= this.total; },
            get yaList() { return this.ids.filter(id => this.jawab[id] === 'ya'); },

            pilih(nilai) {
                if (this.selesai) return;
                this.jawab[this.ids[this.i]] = nilai;
                clearTimeout(this.timer);
                this.timer = setTimeout(() => { this.i++; }, 220);
            },
            mundur() {
                if (this.i > 0) {
                    clearTimeout(this.timer);
                    this.i--;
                }
            },
            tekan(e) {
                if (['INPUT', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) return;
                if (e.key === '1') this.pilih('ya');
                else if (e.key === '2') this.pilih('tidak');
                else if (e.key === '3') this.pilih('tahu');
                else if (e.key === 'ArrowLeft') this.mundur();
            },

            async analisisFoto(e) {
                const file = e.target.files[0];
                e.target.value = ''; // supaya foto yang sama bisa dipilih lagi
                if (!file) return;

                if (this.fotoUrl) URL.revokeObjectURL(this.fotoUrl);
                this.fotoUrl = URL.createObjectURL(file);

                if (!file.type.startsWith('image/')) {
                    this.analisis = { status: 'error', pesan: 'File harus berupa gambar.', semua: [], terisi: [] };
                    return;
                }

                this.analisis = { status: 'memuat', pesan: 'Memuat model (pertama kali agak lama)...', semua: [], terisi: [] };

                try {
                    const model = await siapkanModel(this.foto.model, this.foto.metadata);

                    this.analisis.pesan = 'Menganalisis foto...';

                    const img = new Image();
                    img.src = this.fotoUrl;
                    await img.decode();

                    const prediksi = await model.predict(img);
                    const urut = [...prediksi].sort((a, b) => b.probability - a.probability);
                    const semua = urut.map(p => ({ nama: p.className, persen: Math.round(p.probability * 100) }));
                    const teratas = urut[0];
                    const ambang = this.foto.ambang;

                    // Kelas yang yakin dan punya pemetaan gejala
                    const terdeteksi = urut.filter(p => p.probability >= ambang && this.foto.pemetaan[p.className]);

                    const terisi = [];
                    terdeteksi.forEach(p => {
                        this.foto.pemetaan[p.className].forEach(kode => {
                            const id = this.kodeKeId[kode];   // hanya gejala milik komponen ini
                            if (id && this.jawab[id] !== 'ya') {
                                this.jawab[id] = 'ya';
                                terisi.push(this.nama[id]);
                            }
                        });
                    });

                    let pesan;
                    if (teratas.probability < ambang) {
                        pesan = `Model belum yakin (tertinggi ${Math.round(teratas.probability * 100)}%). Coba foto yang lebih jelas dan terang.`;
                    } else if (terdeteksi.length === 0) {
                        pesan = `Terbaca sebagai "${teratas.className}" (${Math.round(teratas.probability * 100)}%). Tidak ada kerusakan terlihat yang bisa diisi otomatis. Jawab pertanyaan secara manual.`;
                    } else if (terisi.length === 0) {
                        pesan = `Terbaca sebagai "${terdeteksi[0].className}", tetapi komponen ini tidak punya gejala yang cocok. Jawab pertanyaan secara manual.`;
                    } else {
                        pesan = `Terdeteksi "${terdeteksi[0].className}" (${Math.round(terdeteksi[0].probability * 100)}%). Gejala terisi: ${terisi.join(', ')}. Periksa lagi dan lengkapi sisanya.`;
                    }

                    this.analisis = { status: 'selesai', pesan, semua, terisi };
                } catch (err) {
                    console.error(err);
                    this.analisis = {
                        status: 'error',
                        pesan: 'Gagal menganalisis foto. Periksa koneksi internet (pustaka dimuat dari CDN) dan file model, atau jawab pertanyaan secara manual.',
                        semua: [],
                        terisi: [],
                    };
                }
            },
        };
    }
</script>
@endpush