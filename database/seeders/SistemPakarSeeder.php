<?php

namespace Database\Seeders;

use App\Models\Gejala;
use App\Models\Kerusakan;
use App\Models\Komponen;
use App\Models\Rule;
use Illuminate\Database\Seeder;

class SistemPakarSeeder extends Seeder
{
    public function run(): void
    {
        // Komponen
        $komponen = [
            ['C01', 'Kapasitor'],
            ['C02', 'Resistor'],
            ['C03', 'Transistor'],
        ];
        foreach ($komponen as [$kode, $nama]) {
            Komponen::updateOrCreate(['kode' => $kode], ['nama' => $nama]);
        }

        // Gejala
        $gejala = [
            ['G01', 'Bodi menggembung'],
            ['G02', 'Ada cairan elektrolit keluar'],
            ['G03', 'Rangkaian sering restart / mati sendiri'],
            ['G04', 'Komponen gosong atau berubah warna'],
            ['G05', 'Komponen sangat panas'],
            ['G06', 'Nilai hambatan terukur jauh dari nilai di badan'],
            ['G07', 'Tidak ada output / sinyal'],
            ['G08', 'Multimeter menunjukkan short antar kaki'],
            ['G09', 'Tercium bau terbakar'],
        ];
        foreach ($gejala as [$kode, $nama]) {
            Gejala::updateOrCreate(['kode' => $kode], ['nama' => $nama]);
        }

        // Kerusakan
        $kerusakan = [
            ['K01', 'Kapasitor bocor / kembung', 'C01', 'Usia komponen, tegangan berlebih, panas', 'Ganti kapasitor dengan nilai kapasitansi dan tegangan yang sama'],
            ['K02', 'Resistor terbakar / putus', 'C02', 'Arus berlebih, daya melebihi rating', 'Ganti resistor dengan nilai dan watt yang sesuai, cek penyebab arus berlebih'],
            ['K03', 'Transistor short', 'C03', 'Panas berlebih, beban terlalu besar', 'Ganti transistor dan periksa rangkaian bias serta beban'],
        ];
        foreach ($kerusakan as [$kode, $nama, $kodeKomponen, $penyebab, $solusi]) {
            Kerusakan::updateOrCreate(['kode' => $kode], [
                'nama' => $nama,
                'penyebab' => $penyebab,
                'solusi' => $solusi,
                'komponen_id' => Komponen::where('kode', $kodeKomponen)->value('id'),
            ]);
        }

        // Rule: [kode kerusakan, kode gejala, CF pakar]
        $rules = [
            ['K01', 'G01', 0.8],
            ['K01', 'G02', 0.9],
            ['K01', 'G03', 0.6],
            ['K02', 'G04', 0.7],
            ['K02', 'G06', 0.8],
            ['K02', 'G09', 0.6],
            ['K03', 'G05', 0.6],
            ['K03', 'G07', 0.7],
            ['K03', 'G08', 0.9],
        ];
        foreach ($rules as [$k, $g, $cf]) {
            Rule::updateOrCreate([
                'kerusakan_id' => Kerusakan::where('kode', $k)->value('id'),
                'gejala_id'    => Gejala::where('kode', $g)->value('id'),
            ], ['cf_pakar' => $cf]);
        }
    }
}