<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hasil Diagnosa #{{ $diagnosa->id }} - ElecDiag</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white text-slate-800 p-8 max-w-3xl mx-auto">
    @php
        $k = $diagnosa->kerusakan;
    @endphp

    <div class="print:hidden flex gap-2 mb-6">
        <button onclick="window.print()" class="px-4 py-2 rounded bg-slate-900 text-white">Cetak / Simpan PDF</button>
        <button onclick="window.close()" class="px-4 py-2 rounded border border-slate-300">Tutup</button>
    </div>

    <div class="border-b-2 border-slate-800 pb-4 mb-6">
        <h1 class="text-2xl font-bold">ElecDiag</h1>
        <p class="text-sm text-slate-500">Laporan Hasil Diagnosa Kerusakan Komponen Elektronika</p>
    </div>

    <table class="w-full text-sm mb-6">
        <tr><td class="py-1 w-40 text-slate-500">Nomor diagnosa</td><td>#{{ $diagnosa->id }}</td></tr>
        <tr><td class="py-1 text-slate-500">Tanggal</td><td>{{ $diagnosa->created_at->format('d M Y, H:i') }}</td></tr>
        <tr><td class="py-1 text-slate-500">Pengguna</td><td>{{ $diagnosa->user->name ?? 'Tamu' }}</td></tr>
        <tr><td class="py-1 text-slate-500">Komponen</td><td>{{ $k->komponen->nama }}</td></tr>
    </table>

    <div class="border border-slate-300 rounded p-4 mb-6">
        <p class="text-sm text-slate-500">Kemungkinan kerusakan terbesar</p>
        <p class="text-xl font-bold">{{ $k->nama }}</p>
        <p class="mt-1">
            Tingkat keyakinan: <strong>{{ number_format($diagnosa->nilai_cf * 100, 1) }}%</strong>
            &nbsp;|&nbsp; Prioritas: <strong>{{ $k->prioritas }}</strong>
        </p>
    </div>

    <h2 class="font-semibold mb-2">Gejala yang dipilih</h2>
    <ul class="list-disc ml-6 mb-6 text-sm">
        @foreach ($diagnosa->gejalas as $g)
            <li>{{ $g->nama }}</li>
        @endforeach
    </ul>

    <h2 class="font-semibold mb-2">Penyebab potensial</h2>
    <p class="text-sm mb-6">{{ $k->penyebab }}</p>

    <h2 class="font-semibold mb-2">Rekomendasi penanganan</h2>
    <ol class="list-decimal ml-6 mb-6 text-sm space-y-1">
        @forelse ($k->rekomendasis as $r)
            <li>{{ $r->langkah }}</li>
        @empty
            <li>{{ $k->solusi }}</li>
        @endforelse
    </ol>

    <h2 class="font-semibold mb-2">Semua kemungkinan</h2>
    <table class="w-full text-sm border border-slate-300 mb-8">
        <thead>
            <tr class="border-b border-slate-300 text-left">
                <th class="p-2">Kode</th>
                <th class="p-2">Kerusakan</th>
                <th class="p-2 text-right">Keyakinan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($diagnosa->hasil_semua as $h)
                <tr class="border-t border-slate-200">
                    <td class="p-2">{{ $h['kode'] }}</td>
                    <td class="p-2">{{ $h['nama'] }}</td>
                    <td class="p-2 text-right">{{ $h['persen'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="text-xs text-slate-500 border-t border-slate-300 pt-3">
        Hasil ini merupakan perkiraan sistem pakar berdasarkan gejala yang dipilih dan tidak menggantikan
        pemeriksaan langsung oleh teknisi.
    </p>
</body>
</html>