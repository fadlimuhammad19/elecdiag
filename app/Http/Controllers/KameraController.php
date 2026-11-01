<?php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\Komponen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KameraController extends Controller
{
    public function analisis(Request $request, Komponen $komponen)
    {
        $request->validate([
            'foto' => 'required|image|max:6144',
        ]);

        $key = config('gemini.key');
        if (! $key) {
            return response()->json(['pesan' => 'GEMINI_API_KEY belum diisi di .env'], 500);
        }

        $gejalas = Gejala::whereHas('rules.kerusakan', fn ($q) => $q->where('komponen_id', $komponen->id))
            ->orderBy('kode')
            ->get();

        $daftar = $gejalas->map(fn ($g) => [
            'id'     => $g->id,
            'gejala' => $g->nama ?? $g->deskripsi ?? $g->kode,
        ])->values();

        $foto = $request->file('foto');

        // Simpan foto sebagai bukti kerusakan (butuh: php artisan storage:link)
        $path = $foto->store('bukti', 'public');

        $prompt = "Kamu teknisi elektronik berpengalaman yang menganalisis foto kerusakan komponen. "
            . "Komponen: {$komponen->nama}. "
            . "Tugas: (1) jelaskan bukti kerusakan yang BENAR-BENAR terlihat di foto (misalnya hangus, "
            . "retak, menggembung, korosi, jalur putus, solderan lepas), jangan mengarang; "
            . "(2) pilih gejala yang cocok dari daftar berikut: " . $daftar->toJson(JSON_UNESCAPED_UNICODE) . "; "
            . "(3) nilai apakah komponen masih bisa diperbaiki atau sebaiknya diganti; "
            . "(4) jika bisa diperbaiki, beri langkah perbaikan yang praktis dan aman; "
            . "(5) jika tidak, beri alasan dan rekomendasi membeli yang baru. "
            . "Jika foto buram, tidak menampilkan komponen, atau kerusakan tidak terlihat, set kondisi 'tidak_pasti'. "
            . 'Balas HANYA JSON: {"gejala_id":[angka],"bukti":["..."],"kondisi":"bisa_diperbaiki|sebaiknya_diganti|tidak_pasti",'
            . '"langkah_perbaikan":["..."],"rekomendasi":"satu paragraf singkat","tingkat_keyakinan":"rendah|sedang|tinggi"}';

        $url = 'https://generativelanguage.googleapis.com/v1beta/models/' . config('gemini.model') . ':generateContent';

        try {
            $res = Http::timeout(90)
                ->withHeaders(['x-goog-api-key' => $key])
                ->post($url, [
                    'contents' => [[
                        'parts' => [
                            ['text' => $prompt],
                            ['inline_data' => [
                                'mime_type' => $foto->getMimeType(),
                                'data'      => base64_encode(file_get_contents($foto->getRealPath())),
                            ]],
                        ],
                    ]],
                    'generationConfig' => ['responseMimeType' => 'application/json'],
                ]);
        } catch (\Throwable $e) {
            return response()->json(['pesan' => 'Gagal menghubungi layanan AI: ' . $e->getMessage()], 502);
        }

        if (! $res->successful()) {
            return response()->json([
                'pesan'  => 'Layanan AI menolak permintaan.',
                'detail' => $res->json('error.message'),
            ], 502);
        }

        $data = json_decode($res->json('candidates.0.content.parts.0.text') ?? '', true);

        if (! is_array($data)) {
            return response()->json(['pesan' => 'Jawaban AI tidak bisa dibaca, coba foto ulang.'], 502);
        }

        $valid = $gejalas->pluck('id')->all();
        $ids = collect($data['gejala_id'] ?? [])
            ->map(fn ($i) => (int) $i)
            ->filter(fn ($i) => in_array($i, $valid, true))
            ->unique()
            ->values();

        $kondisi = in_array($data['kondisi'] ?? '', ['bisa_diperbaiki', 'sebaiknya_diganti', 'tidak_pasti'], true)
            ? $data['kondisi']
            : 'tidak_pasti';

        return response()->json([
            'foto_url'           => asset('storage/' . $path),
            'gejala_id'          => $ids,
            'bukti'              => array_values((array) ($data['bukti'] ?? [])),
            'kondisi'            => $kondisi,
            'langkah_perbaikan'  => array_values((array) ($data['langkah_perbaikan'] ?? [])),
            'rekomendasi'        => $data['rekomendasi'] ?? '',
            'tingkat_keyakinan'  => $data['tingkat_keyakinan'] ?? 'rendah',
        ]);
    }
}