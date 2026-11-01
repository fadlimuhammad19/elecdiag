<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('kode') - ElecDiag</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { color-scheme: dark; }
        .grid-bg {
            background-image:
                linear-gradient(rgba(139,92,246,.07) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139,92,246,.07) 1px, transparent 1px);
            background-size: 40px 40px;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-200 antialiased min-h-screen grid-bg flex items-center justify-center p-4 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-violet-600/20 blur-3xl rounded-full pointer-events-none"></div>

    <div class="relative text-center max-w-md">
        <p class="text-8xl font-bold text-violet-400">@yield('kode')</p>
        <h1 class="text-2xl font-semibold text-white mt-4">@yield('judul')</h1>
        <p class="text-zinc-400 mt-2">@yield('pesan')</p>

        <div class="mt-8 flex flex-wrap justify-center gap-3">
            <a href="{{ url('/') }}" class="bg-violet-500 hover:bg-violet-600 text-white px-5 py-2 rounded-lg">Ke Beranda</a>
            <button type="button" onclick="history.length > 1 ? history.back() : (window.location = '{{ url('/') }}')"
                    class="border border-zinc-700 text-zinc-300 hover:bg-zinc-900 px-5 py-2 rounded-lg">Halaman Sebelumnya</button>
        </div>
    </div>
</body>
</html>