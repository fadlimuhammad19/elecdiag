<?php

namespace Database\Seeders;

use App\Models\Gejala;
use App\Models\Kategori;
use App\Models\Kerusakan;
use App\Models\Komponen;
use App\Models\Rekomendasi;
use App\Models\Rule;
use Illuminate\Database\Seeder;

class DataPakarSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- KATEGORI ----------
        $kategori = [
            ['KT01', 'Komponen Dasar', 'Resistor, kapasitor, transistor, dan komponen dasar lainnya'],
            ['KT02', 'Mikrokontroler', 'Arduino dan board sejenis'],
            ['KT03', 'Laptop', 'Laptop beserta aksesorisnya'],
        ];
        foreach ($kategori as [$kode, $nama, $deskripsi]) {
            Kategori::updateOrCreate(['kode' => $kode], ['nama' => $nama, 'deskripsi' => $deskripsi]);
        }

        // ---------- KOMPONEN ----------
        // [kode, kode kategori, nama, jenis arus]
        $komponen = [
            ['C01', 'KT01', 'Kapasitor', 'AC/DC'],
            ['C02', 'KT01', 'Resistor', 'AC/DC'],
            ['C03', 'KT01', 'Transistor', 'DC'],
            ['C04', 'KT02', 'Arduino Uno', 'DC'],
            ['C05', 'KT03', 'Charger Laptop', 'AC/DC'],
            ['C06', 'KT03', 'Baterai Laptop', 'DC'],
            ['C07', 'KT03', 'RAM Laptop', 'DC'],
        ];
        foreach ($komponen as [$kode, $kodeKategori, $nama, $arus]) {
            Komponen::updateOrCreate(['kode' => $kode], [
                'kategori_id' => Kategori::where('kode', $kodeKategori)->value('id'),
                'nama'        => $nama,
                'jenis_arus'  => $arus,
            ]);
        }

        // ---------- GEJALA ----------
        // [kode, nama, pertanyaan]
        $gejala = [
            ['G01', 'Bodi menggembung', 'Apakah bodi komponen terlihat menggembung?'],
            ['G02', 'Ada cairan elektrolit keluar', 'Apakah ada cairan atau residu yang keluar dari komponen?'],
            ['G03', 'Rangkaian sering restart / mati sendiri', 'Apakah rangkaian sering restart atau mati sendiri?'],
            ['G04', 'Komponen gosong atau berubah warna', 'Apakah komponen tampak gosong atau berubah warna?'],
            ['G05', 'Komponen sangat panas', 'Apakah komponen terasa sangat panas saat rangkaian menyala?'],
            ['G06', 'Nilai hambatan jauh dari nilai di badan', 'Apakah hasil ukur multimeter jauh berbeda dari nilai yang tertera?'],
            ['G07', 'Tidak ada output / sinyal', 'Apakah rangkaian tidak menghasilkan output atau sinyal?'],
            ['G08', 'Short antar kaki', 'Apakah multimeter menunjukkan hubungan singkat (short) antar kaki komponen?'],
            ['G09', 'Tercium bau terbakar', 'Apakah tercium bau terbakar dari komponen?'],

            ['G10', 'Board tidak menyala', 'Apakah LED power (ON) pada board mati total?'],
            ['G11', 'Regulator tegangan panas', 'Apakah regulator tegangan terasa sangat panas?'],
            ['G12', 'Tegangan 5V tidak keluar', 'Apakah pin 5V tidak mengeluarkan tegangan saat diukur?'],
            ['G13', 'Port tidak terdeteksi di komputer', 'Apakah board tidak muncul sebagai port di komputer?'],
            ['G14', 'LED ON menyala normal', 'Apakah LED power (ON) pada board menyala normal?'],
            ['G15', 'Upload program gagal', 'Apakah upload program selalu gagal?'],
            ['G16', 'Satu pin tidak merespons', 'Apakah ada satu pin yang tidak merespons?'],
            ['G17', 'Pin selalu HIGH atau LOW', 'Apakah pin tertentu selalu berada di HIGH atau LOW?'],
            ['G18', 'Pin lain masih normal', 'Apakah pin-pin lainnya masih berfungsi normal?'],
            ['G19', 'Error "not in sync"', 'Apakah muncul error "not in sync" saat upload?'],
            ['G20', 'Port terdeteksi di komputer', 'Apakah board terdeteksi sebagai port di komputer?'],

            ['G21', 'Laptop tidak mengisi baterai', 'Apakah baterai laptop tidak terisi saat charger terpasang?'],
            ['G22', 'Lampu indikator charger mati', 'Apakah lampu indikator charger atau laptop tidak menyala saat dicolok?'],
            ['G23', 'Adaptor sangat panas atau berbau', 'Apakah adaptor charger sangat panas atau berbau hangus?'],
            ['G24', 'Laptop mati saat charger dicabut', 'Apakah laptop langsung mati saat charger dicabut?'],
            ['G25', 'Persentase baterai turun cepat', 'Apakah persentase baterai turun sangat cepat?'],
            ['G26', 'Baterai menggembung', 'Apakah baterai atau bodi laptop terlihat menggembung?'],
            ['G27', 'Layar hitam disertai bunyi beep', 'Apakah layar hitam disertai bunyi beep saat dinyalakan?'],
            ['G28', 'Laptop restart sendiri', 'Apakah laptop sering restart sendiri?'],
            ['G29', 'Muncul blue screen', 'Apakah sering muncul blue screen?'],
        ];
        foreach ($gejala as [$kode, $nama, $pertanyaan]) {
            Gejala::updateOrCreate(['kode' => $kode], ['nama' => $nama, 'pertanyaan' => $pertanyaan]);
        }

        // ---------- KERUSAKAN + RULE + REKOMENDASI ----------
        $kerusakan = [
            [
                'kode' => 'K01', 'komponen' => 'C01', 'nama' => 'Kapasitor bocor / kembung', 'prioritas' => 'Tinggi',
                'penyebab' => 'Usia komponen, tegangan berlebih, panas',
                'solusi' => 'Ganti kapasitor dengan nilai kapasitansi dan tegangan yang sama',
                'rules' => ['G01' => 0.8, 'G02' => 0.9, 'G03' => 0.6],
                'rekomendasi' => [
                    'Matikan dan cabut sumber daya, tunggu sampai kapasitor mengosongkan muatan.',
                    'Lepas kapasitor, catat nilai kapasitansi dan tegangan kerjanya.',
                    'Pasang kapasitor baru dengan nilai yang sama (tegangan boleh lebih tinggi) dan perhatikan polaritas.',
                ],
            ],
            [
                'kode' => 'K02', 'komponen' => 'C02', 'nama' => 'Resistor terbakar / putus', 'prioritas' => 'Sedang',
                'penyebab' => 'Arus berlebih, daya melebihi rating',
                'solusi' => 'Ganti resistor dengan nilai dan watt yang sesuai',
                'rules' => ['G04' => 0.7, 'G06' => 0.8, 'G09' => 0.6],
                'rekomendasi' => [
                    'Matikan daya, lalu lepas resistor dari rangkaian.',
                    'Ukur dengan multimeter dan bandingkan dengan nilai pada kode warna.',
                    'Ganti dengan nilai dan rating watt yang sama atau lebih besar, lalu cari penyebab arus berlebih.',
                ],
            ],
            [
                'kode' => 'K03', 'komponen' => 'C03', 'nama' => 'Transistor short', 'prioritas' => 'Tinggi',
                'penyebab' => 'Panas berlebih, beban terlalu besar',
                'solusi' => 'Ganti transistor dan periksa rangkaian bias serta beban',
                'rules' => ['G05' => 0.6, 'G07' => 0.7, 'G08' => 0.9],
                'rekomendasi' => [
                    'Matikan daya dan lepas transistor dari rangkaian.',
                    'Ukur tiap pasang kaki dengan mode dioda di multimeter.',
                    'Ganti dengan tipe yang sama (cek datasheet), lalu periksa beban dan resistor basis.',
                ],
            ],
            [
                'kode' => 'K04', 'komponen' => 'C04', 'nama' => 'Regulator tegangan rusak', 'prioritas' => 'Tinggi',
                'penyebab' => 'Tegangan input berlebih, short di jalur 5V, panas',
                'solusi' => 'Ganti regulator dan periksa komponen di sekitarnya',
                'rules' => ['G10' => 0.7, 'G11' => 0.8, 'G12' => 0.9],
                'rekomendasi' => [
                    'Cabut semua sumber daya, lalu ukur tegangan input dan output regulator.',
                    'Periksa kapasitor dan dioda proteksi di sekitar regulator apakah ada yang short.',
                    'Ganti regulator, lalu pastikan jalur 5V tidak short sebelum dinyalakan lagi.',
                ],
            ],
            [
                'kode' => 'K05', 'komponen' => 'C04', 'nama' => 'Chip USB rusak', 'prioritas' => 'Sedang',
                'penyebab' => 'Lonjakan tegangan lewat USB, solder lepas',
                'solusi' => 'Ganti chip USB atau upload lewat programmer eksternal',
                'rules' => ['G13' => 0.8, 'G14' => 0.5, 'G15' => 0.7],
                'rekomendasi' => [
                    'Coba kabel USB dan port komputer yang lain.',
                    'Instal ulang driver USB serial yang sesuai dengan board.',
                    'Jika tetap tidak terdeteksi, ganti chip USB atau upload memakai programmer ISP/FTDI.',
                ],
            ],
            [
                'kode' => 'K06', 'komponen' => 'C04', 'nama' => 'Pin I/O rusak', 'prioritas' => 'Rendah',
                'penyebab' => 'Arus berlebih pada pin, tegangan melebihi batas, ESD',
                'solusi' => 'Gunakan pin cadangan atau ganti mikrokontroler',
                'rules' => ['G16' => 0.8, 'G17' => 0.7, 'G18' => 0.5],
                'rekomendasi' => [
                    'Uji pin dengan sketch sederhana dan bandingkan dengan pin lain.',
                    'Ukur tegangan pin dengan multimeter saat diberi HIGH dan LOW.',
                    'Pindahkan fungsi ke pin cadangan, atau ganti mikrokontroler jika pin penting rusak.',
                ],
            ],
            [
                'kode' => 'K07', 'komponen' => 'C04', 'nama' => 'Bootloader rusak', 'prioritas' => 'Rendah',
                'penyebab' => 'Upload terputus, fuse bit salah',
                'solusi' => 'Burn ulang bootloader',
                'rules' => ['G19' => 0.9, 'G20' => 0.6, 'G15' => 0.6, 'G14' => 0.4],
                'rekomendasi' => [
                    'Pastikan board dan processor yang dipilih di Arduino IDE sudah benar.',
                    'Coba tekan tombol reset tepat saat proses upload dimulai.',
                    'Burn ulang bootloader memakai programmer ISP.',
                ],
            ],
            [
                'kode' => 'K08', 'komponen' => 'C05', 'nama' => 'Adaptor charger rusak', 'prioritas' => 'Sedang',
                'penyebab' => 'Kabel patah, komponen dalam adaptor rusak, panas berlebih',
                'solusi' => 'Ganti adaptor dengan spesifikasi yang sama',
                'rules' => ['G21' => 0.6, 'G22' => 0.8, 'G23' => 0.7],
                'rekomendasi' => [
                    'Periksa kabel dan konektor charger apakah ada yang putus atau longgar.',
                    'Ukur tegangan output adaptor dan bandingkan dengan label.',
                    'Ganti adaptor dengan voltase dan ampere yang sama.',
                ],
            ],
            [
                'kode' => 'K09', 'komponen' => 'C06', 'nama' => 'Baterai drop / kembung', 'prioritas' => 'Tinggi',
                'penyebab' => 'Usia pakai, siklus cas berlebihan, panas',
                'solusi' => 'Ganti baterai dengan tipe yang sesuai',
                'rules' => ['G24' => 0.8, 'G25' => 0.7, 'G26' => 0.9],
                'rekomendasi' => [
                    'Jika baterai menggembung, berhenti memakainya dan jangan dicas karena berisiko terbakar.',
                    'Cek kondisi baterai lewat laporan baterai di sistem operasi.',
                    'Ganti dengan baterai yang sesuai tipe laptop, jangan menusuk atau memaksa membukanya.',
                ],
            ],
            [
                'kode' => 'K10', 'komponen' => 'C07', 'nama' => 'RAM bermasalah', 'prioritas' => 'Sedang',
                'penyebab' => 'Kontak kotor, modul rusak, tidak kompatibel',
                'solusi' => 'Bersihkan kontak atau ganti modul RAM',
                'rules' => ['G27' => 0.8, 'G28' => 0.5, 'G29' => 0.7],
                'rekomendasi' => [
                    'Matikan laptop, lepas RAM, lalu bersihkan kontak emas dengan penghapus lembut.',
                    'Pasang ulang, atau coba satu keping bergantian untuk menemukan yang rusak.',
                    'Jalankan tes memori (misalnya MemTest86) dan ganti modul yang gagal.',
                ],
            ],
        ];

        foreach ($kerusakan as $k) {
            $model = Kerusakan::updateOrCreate(['kode' => $k['kode']], [
                'komponen_id' => Komponen::where('kode', $k['komponen'])->value('id'),
                'nama'        => $k['nama'],
                'prioritas'   => $k['prioritas'],
                'penyebab'    => $k['penyebab'],
                'solusi'      => $k['solusi'],
            ]);

            foreach ($k['rules'] as $kodeGejala => $cf) {
                Rule::updateOrCreate([
                    'kerusakan_id' => $model->id,
                    'gejala_id'    => Gejala::where('kode', $kodeGejala)->value('id'),
                ], ['cf_pakar' => $cf]);
            }

            Rekomendasi::where('kerusakan_id', $model->id)->delete();
            foreach ($k['rekomendasi'] as $i => $langkah) {
                Rekomendasi::create([
                    'kerusakan_id' => $model->id,
                    'urutan'       => $i + 1,
                    'langkah'      => $langkah,
                ]);
            }
        }
    }
}