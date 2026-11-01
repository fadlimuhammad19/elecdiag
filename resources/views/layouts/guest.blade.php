<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - ElecDiag</title>
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
<body class="bg-zinc-950 text-zinc-200 antialiased min-h-screen grid-bg flex items-center justify-center p-4 relative">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[300px] bg-violet-600/20 blur-3xl rounded-full pointer-events-none"></div>

    <div class="relative w-full max-w-md bg-zinc-900/80 backdrop-blur border border-zinc-800 rounded-2xl p-8">
        <a href="{{ route('landing') }}" class="flex items-center justify-center gap-2 mb-6">
            <div class="w-9 h-9 rounded-lg bg-violet-500 flex items-center justify-center">
                <i data-lucide="cpu" class="w-5 h-5 text-white"></i>
            </div>
            <span class="text-xl font-semibold text-white">ElecDiag</span>
        </a>

        @yield('content')
    </div>

    <script>lucide.createIcons();</script>
</body>
</html>