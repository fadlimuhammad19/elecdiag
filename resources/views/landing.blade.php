<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ElecDiag - Sistem Pakar Kerusakan Elektronika</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .grid-bg {
            background-image:
                linear-gradient(rgba(139,92,246,.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139,92,246,.07) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-200 antialiased">

    {{-- Navbar --}}
    <nav class="max-w-6xl mx-auto flex items-center justify-between px-6 py-5">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-lg bg-violet-500 flex items-center justify-center">
                <i data-lucide="cpu" class="w-4 h-4 text-white"></i>
            </div>
            <span class="text-lg font-semibold text-white">ElecDiag</span>
        </div>
        <div class="flex items-center gap-3 text-sm">
            @auth
                <a href="{{ route('dashboard') }}" class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-zinc-300 hover:text-white">Login</a>
                <a href="{{ route('register') }}" class="bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">Daftar</a>
            @endauth
        </div>
    </nav>

    {{-- Hero --}}
    <header class="relative grid-bg overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[700px] h-[350px] bg-violet-600/25 blur-3xl rounded-full pointer-events-none"></div>

        <div class="relative max-w-6xl mx-auto px-6 py-24 md:py-32">
            <span class="inline-flex items-center gap-2 text-xs px-3 py-1 rounded-full border border-violet-500/40 bg-violet-500/10 text-violet-300 mb-6">
                <i data-lucide="sparkles" class="w-3 h-3"></i> Sistem pakar berbasis Certainty Factor
            </span>
            <h1 class="text-4xl md:text-6xl font-bold text-white leading-tight max-w-3xl">
                Diagnosis kerusakan <span class="text-violet-400">komponen elektronika</span>
            </h1>
            <p class="mt-5 text-zinc-400 max-w-xl text-lg">
                Dapatkan analisis kerusakan dan rekomendasi penanganan berdasarkan gejala yang Anda alami.
            </p>
            <div class="mt-8 flex flex-wrap gap-3">
                <a href="{{ route('diagnosa.index') }}"
                   class="bg-violet-500 hover:bg-violet-600 text-white px-6 py-3 rounded-lg font-medium transition">Mulai Konsultasi</a>
                <a href="#cara-kerja"
                   class="border border-zinc-700 hover:bg-zinc-900 text-zinc-300 px-6 py-3 rounded-lg transition">Pelajari Lebih Lanjut</a>
            </div>
        </div>
    </header>

    {{-- Kategori --}}
    <section class="max-w-6xl mx-auto px-6 py-16">
        <h2 class="text-xl font-semibold text-white mb-6">Kategori yang didukung</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            @foreach ([
                ['Komponen Dasar', 'zap'],
                ['Mikrokontroler', 'circuit-board'],
                ['Laptop', 'laptop'],
                ['Smartphone', 'smartphone'],
                ['Komputer', 'monitor'],
            ] as [$nama, $icon])
                <div class="bg-zinc-900 border border-zinc-800 hover:border-violet-500 rounded-2xl p-5 text-center transition">
                    <div class="w-10 h-10 mx-auto rounded-full bg-zinc-800 flex items-center justify-center mb-3">
                        <i data-lucide="{{ $icon }}" class="w-5 h-5 text-violet-400"></i>
                    </div>
                    <p class="text-sm text-zinc-300">{{ $nama }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Cara kerja --}}
    <section id="cara-kerja" class="max-w-6xl mx-auto px-6 pb-20">
        <h2 class="text-xl font-semibold text-white mb-6">Cara kerja</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach ([
                ['1', 'Pilih komponen', 'Tentukan komponen atau perangkat yang bermasalah.'],
                ['2', 'Jawab pertanyaan', 'Jawab pertanyaan ya/tidak tentang gejala yang Anda amati.'],
                ['3', 'Lihat hasil', 'Dapatkan kemungkinan kerusakan, persentase keyakinan, dan langkah penanganan.'],
            ] as [$no, $judul, $isi])
                <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
                    <div class="w-8 h-8 rounded-full bg-violet-500 text-white flex items-center justify-center font-semibold mb-4">{{ $no }}</div>
                    <h3 class="font-semibold text-white mb-1">{{ $judul }}</h3>
                    <p class="text-sm text-zinc-400">{{ $isi }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <footer class="border-t border-zinc-800 py-6 text-center text-sm text-zinc-500">
        © {{ date('Y') }} ElecDiag. Sistem Pakar Kerusakan Elektronika.
    </footer>

    <script>lucide.createIcons();</script>
</body>
</html>