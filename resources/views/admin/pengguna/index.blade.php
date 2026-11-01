@extends('layouts.app')
@section('title', 'Pengguna')

@section('content')
    @php
        $input = 'bg-zinc-900 border border-zinc-800 rounded-lg px-3 py-2 text-sm text-zinc-200 focus:outline-none focus:border-violet-500';
    @endphp

    @if (session('sukses'))
        <div class="bg-green-500/10 text-green-400 p-3 rounded-lg mb-4 text-sm">{{ session('sukses') }}</div>
    @endif
    @if (session('error'))
        <div class="bg-red-500/10 text-red-400 p-3 rounded-lg mb-4 text-sm">{{ session('error') }}</div>
    @endif

    <form method="GET" class="mb-4">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau email..."
               class="{{ $input }} w-full max-w-sm">
    </form>

    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="text-zinc-500 text-xs uppercase">
                <tr>
                    <th class="pb-3 font-medium">Pengguna</th>
                    <th class="pb-3 font-medium">Role</th>
                    <th class="pb-3 font-medium">Konsultasi</th>
                    <th class="pb-3 font-medium">Bergabung</th>
                    <th class="pb-3 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($penggunas as $u)
                    @php $saya = $u->id === auth()->id(); @endphp
                    <tr class="border-t border-zinc-800">
                        <td class="py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-violet-500/20 text-violet-300 flex items-center justify-center text-sm font-semibold">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-white">{{ $u->name }} @if ($saya)<span class="text-xs text-zinc-500">(Anda)</span>@endif</p>
                                    <p class="text-xs text-zinc-500">{{ $u->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-3">
                            <form action="{{ route('admin.pengguna.update', $u) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="role" onchange="this.form.submit()" @disabled($saya) class="{{ $input }} disabled:opacity-50">
                                    <option value="user" @selected($u->role === 'user')>user</option>
                                    <option value="admin" @selected($u->role === 'admin')>admin</option>
                                </select>
                            </form>
                        </td>
                        <td class="py-3">{{ $jumlah[$u->id] ?? 0 }}</td>
                        <td class="py-3 text-zinc-400 whitespace-nowrap">{{ $u->created_at->format('d M Y') }}</td>
                        <td class="py-3 text-right">
                            @unless ($saya)
                                <form action="{{ route('admin.pengguna.destroy', $u) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus pengguna {{ $u->name }}? Riwayat diagnosanya tetap tersimpan sebagai tamu.')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-400 hover:underline">Hapus</button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-8 text-center text-zinc-500">Tidak ada pengguna.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $penggunas->links() }}</div>
@endsection