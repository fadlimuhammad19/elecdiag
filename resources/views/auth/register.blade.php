@extends('layouts.guest')
@section('title', 'Daftar')

@section('content')
    <h1 class="text-center text-lg font-semibold text-white mb-1">Buat akun baru</h1>
    <p class="text-center text-sm text-zinc-400 mb-6">Daftar gratis untuk mulai konsultasi.</p>

    @if ($errors->any())
        <div class="bg-red-500/10 text-red-400 p-3 rounded-lg mb-4 text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    @php
        $input = 'w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500';
    @endphp

    <form action="{{ route('register') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm text-zinc-300 mb-1">Nama lengkap</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="{{ $input }}">
        </div>
        <div>
            <label class="block text-sm text-zinc-300 mb-1">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="{{ $input }}">
        </div>
        <div>
            <label class="block text-sm text-zinc-300 mb-1">Password</label>
            <input type="password" name="password" required placeholder="Minimal 8 karakter" class="{{ $input }}">
        </div>
        <div>
            <label class="block text-sm text-zinc-300 mb-1">Konfirmasi password</label>
            <input type="password" name="password_confirmation" required class="{{ $input }}">
        </div>
        <button class="w-full bg-violet-500 hover:bg-violet-600 text-white py-2 rounded-lg font-medium transition">Daftar</button>
    </form>

    <p class="text-center text-sm text-zinc-400 mt-5">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-violet-400 hover:underline">Login</a>
    </p>
@endsection