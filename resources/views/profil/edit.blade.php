@extends('layouts.app')
@section('title', 'Profil')

@section('content')
    @php
        $input = 'w-full bg-zinc-950 border border-zinc-700 rounded-lg px-3 py-2 text-zinc-200 focus:outline-none focus:border-violet-500';
    @endphp

    @if (session('sukses'))
        <div class="bg-green-500/10 text-green-400 p-3 rounded-lg mb-4 text-sm">{{ session('sukses') }}</div>
    @endif

    <div class="grid lg:grid-cols-2 gap-4 max-w-4xl">

        {{-- Informasi akun --}}
        <form action="{{ route('profil.update') }}" method="POST"
              class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6">
            @csrf
            @method('PUT')

            <div class="flex items-center gap-4 mb-6">
                <div class="w-14 h-14 rounded-full bg-violet-500 text-white flex items-center justify-center text-xl font-semibold">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-semibold text-white">Informasi Akun</h2>
                    <p class="text-xs text-zinc-500">Bergabung {{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>

            @if ($errors->hasAny(['name', 'email']))
                <div class="bg-red-500/10 text-red-400 p-3 rounded-lg mb-4 text-sm space-y-1">
                    @foreach ($errors->only(['name', 'email']) as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">Nama lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="{{ $input }}">
                </div>
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="{{ $input }}">
                </div>
            </div>

            <button class="mt-6 bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">Simpan Perubahan</button>
        </form>

        {{-- Ubah password --}}
        <form action="{{ route('profil.password') }}" method="POST"
              class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 h-fit">
            @csrf
            @method('PUT')

            <h2 class="font-semibold text-white mb-6">Ubah Password</h2>

            @if ($errors->hasAny(['password_lama', 'password']))
                <div class="bg-red-500/10 text-red-400 p-3 rounded-lg mb-4 text-sm space-y-1">
                    @foreach ($errors->only(['password_lama', 'password']) as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">Password lama</label>
                    <input type="password" name="password_lama" required class="{{ $input }}">
                </div>
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">Password baru</label>
                    <input type="password" name="password" required placeholder="Minimal 8 karakter" class="{{ $input }}">
                </div>
                <div>
                    <label class="block text-sm text-zinc-300 mb-1">Konfirmasi password baru</label>
                    <input type="password" name="password_confirmation" required class="{{ $input }}">
                </div>
            </div>

            <button class="mt-6 bg-violet-500 hover:bg-violet-600 text-white px-4 py-2 rounded-lg">Ubah Password</button>
        </form>
    </div>
@endsection