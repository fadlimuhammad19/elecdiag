@extends('layouts.guest')
@section('title', 'Masuk')

@section('content')
    <h1 class="text-center text-lg font-semibold text-white mb-1">Masuk ke akun Anda</h1>
    <p class="text-center text-sm text-zinc-400 mb-6">Lanjutkan konsultasi kerusakan komponen.</p>

    @if ($errors->any())
        <div class="bg-red-500/10 text-red-400 p-3 rounded-lg mb-4 text-sm">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('login') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm text-zinc-300 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required placeholder="nama@email.com"
                   class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200
                          focus:outline-none focus:border-violet-500">
        </div>
        <div>
            <label class="block text-sm text-zinc-300 mb-1">Password</label>
            <input type="password" name="password" required placeholder="Masukkan password"
                   class="w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200
                          focus:outline-none focus:border-violet-500">
        </div>
        <label class="flex items-center gap-2 text-sm text-zinc-400">
            <input type="checkbox" name="remember" class="accent-violet-500"> Ingat saya
        </label>
        <button class="w-full bg-violet-500 hover:bg-violet-600 text-white py-2 rounded-lg font-medium transition">Login</button>
    </form>

    <p class="text-center text-sm text-zinc-400 mt-5">
        Belum punya akun? <a href="{{ route('register') }}" class="text-violet-400 hover:underline">Daftar</a>
    </p>
@endsection