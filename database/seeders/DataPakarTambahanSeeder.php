<?php

namespace Database\Seeders;

use App\Models\Gejala;
use App\Models\Kategori;
use App\Models\Kerusakan;
use App\Models\Komponen;
use App\Models\Rekomendasi;
use App\Models\Rule;
use Illuminate\Database\Seeder;

class DataPakarTambahanSeeder extends Seeder
{
    public function run(): void
    {
        // ---------- KATEGORI ----------
        $kategori = [
            ['KT04', 'Smartphone', 'Ponsel pintar beserta komponen utamanya'],
            ['KT05', 'Komputer', 'Komputer desktop dan komponennya'],
        ];
        foreach ($kategori as [$kode, $nama, $deskripsi]) {
            Kategori::updateOrCreate(['kode' => $kode], ['nama' => $nama, 'deskripsi' => $deskripsi]);
        }

        // ---------- KOMPONEN ----------
        $komponen = [
            ['C08', 'KT04', 'Baterai HP', 'DC'],
            ['C09', 'KT04', 'Layar HP', 'DC'],
            ['C10', 'KT04', 'Port Charger HP', 'DC'],
            ['C11', 'KT05', 'Power Supply PC', 'AC/DC'],
            ['C12', 'KT05', 'Harddisk PC', 'DC'],
        ];
        foreach ($komponen as [$kode, $kodeKategori, $nama, $arus]) {
            Komponen::updateOrCreate(['kode' => $kode], [
                'kategori_id' => Kategori::where('kode', $kodeKategori)->value('id'),
                'nama'        => $nama,
                'jenis_arus'  => $arus,
            ]);
        }

        // ---------- GEJALA ----------
        $gejala = [
            // Baterai HP
            ['G30', 'Baterai cepat habis', 'Apakah baterai HP cepat habis meski pemakaian ringan?'],
            ['G31', 'Persentase baterai turun drastis', 'Apakah persentase baterai turun drastis atau meloncat tiba-tiba?'],
            ['G32', 'HP mati sendiri padahal baterai masih ada', 'Apakah HP mati sendiri padahal indikator baterai masih tersisa?'],
            ['G33', 'Bodi HP menggembung', 'Apakah bodi HP terlihat menggembung atau layar terangkat?'],
            ['G34', 'Area baterai panas', 'Apakah bagian baterai terasa panas saat dipegang?'],

            // Layar HP
            ['G35', 'Layar retak', 'Apakah layar HP retak?'],
            ['G36', 'Garis atau bercak warna di layar', 'Apakah muncul garis atau bercak warna di layar?'],
            ['G37', 'Layar tidak merespons sentuhan', 'Apakah layar tidak merespons sentuhan atau bergerak sendiri?'],
            ['G38', 'Layar gelap tapi HP masih hidup', 'Apakah layar gelap total tetapi HP masih bergetar atau berbunyi?'],
            ['G39', 'Layar berkedip', 'Apakah layar berkedip atau meredup sendiri?'],

            // Port charger HP
            ['G40', 'Charger harus digoyang agar mengisi', 'Apakah charger harus digoyang atau diposisikan tertentu agar mengisi daya?'],
            ['G41', 'Pengisian daya lambat', 'Apakah pengisian daya jauh lebih lambat dari biasanya?'],
            ['G42', 'Peringatan kotoran atau cairan di port', 'Apakah muncul peringatan ada kotoran atau cairan di port charger?'],
            ['G43', 'Port terasa longgar', 'Apakah lubang port terasa longgar atau goyang saat kabel dipasang?'],
            ['G44', 'Tidak mengisi dengan charger apa pun', 'Apakah HP tidak mengisi sama sekali dengan semua charger yang dicoba?'],

            // Power supply PC
            ['G45', 'Komputer tidak menyala sama sekali', 'Apakah komputer tidak menyala sama sekali saat tombol power ditekan?'],
            ['G46', 'Komputer mati saat beban berat', 'Apakah komputer restart atau mati sendiri saat dipakai berat (misalnya game)?'],
            ['G47', 'Kipas power supply tidak berputar', 'Apakah kipas power supply tidak berputar?'],
            ['G48', 'Bau terbakar dari power supply', 'Apakah tercium bau terbakar dari arah power supply?'],
            ['G49', 'Suara berdengung dari power supply', 'Apakah terdengar suara berdengung atau berisik dari power supply?'],

            // Harddisk PC
            ['G50', 'Komputer sangat lambat saat membuka file', 'Apakah komputer sangat lambat saat membuka file atau booting?'],
            ['G51', 'Bunyi klik atau gesekan dari harddisk', 'Apakah terdengar bunyi klik atau gesekan berulang dari harddisk?'],
            ['G52', 'File sering rusak atau hilang', 'Apakah file sering rusak, hilang, atau gagal dibuka?'],
            ['G53', 'Disk tidak terdeteksi', 'Apakah muncul pesan "no boot device" atau disk tidak terdeteksi di BIOS?'],
            ['G54', 'Peringatan SMART error', 'Apakah muncul peringatan SMART atau "disk failure"?'],
        ];
        foreach ($gejala as [$kode, $nama, $pertanyaan]) {
            Gejala::updateOrCreate(['kode' => $kode], ['nama' => $nama, 'pertanyaan' => $pertanyaan]);
        }

        // ---------- KERUSAKAN + RULE + REKOMENDASI ----------
        $kerusakan = [
            [
                'kode' => 'K11', 'komponen' => 'C08', 'nama' => 'Baterai HP drop', 'prioritas' => 'Sedang',
                'penyebab' => 'Usia pakai dan siklus pengisian yang banyak',
                'solusi' => 'Ganti baterai dengan tipe yang sesuai',
                'rules' => ['G30' => 0.8, 'G31' => 0.6, 'G32' => 0.7],
                'rekomendasi' => [
                    'Cek kesehatan baterai lewat menu pengaturan atau aplikasi diagnosis bawaan, lalu tutup aplikasi yang boros daya.',
                    'Kalibrasi dengan menghabiskan baterai sampai mati, lalu isi penuh tanpa gangguan.',
                    'Jika tetap boros atau mati sendiri, ganti baterai dengan tipe yang sesuai model HP.',
                ],
            ],
            [
                'kode' => 'K12', 'komponen' => 'C08', 'nama' => 'Baterai HP kembung', 'prioritas' => 'Tinggi',
                'penyebab' => 'Sel baterai rusak, pengisian berlebih, atau panas berlebih',
                'solusi' => 'Hentikan pemakaian dan ganti baterai di service center',
                'rules' => ['G33' => 0.95, 'G34' => 0.6, 'G32' => 0.4],
                'rekomendasi' => [
                    'Hentikan pemakaian dan jangan mengisi daya karena berisiko terbakar.',
                    'Jangan menusuk, menekan, atau memaksa membuka baterai.',
                    'Bawa ke service center untuk penggantian, dan buang baterai lama ke tempat pembuangan khusus.',
                ],
            ],
            [
                'kode' => 'K13', 'komponen' => 'C09', 'nama' => 'Panel layar (LCD) rusak', 'prioritas' => 'Sedang',
                'penyebab' => 'Benturan, tekanan pada layar, atau kerusakan panel',
                'solusi' => 'Ganti panel layar di service center',
                'rules' => ['G35' => 0.6, 'G36' => 0.9, 'G38' => 0.6],
                'rekomendasi' => [
                    'Ambil screenshot. Jika garis tidak muncul di screenshot, kerusakannya ada di panel fisik.',
                    'Hindari menekan area layar yang bergaris atau berbercak.',
                    'Ganti panel layar di service center.',
                ],
            ],
            [
                'kode' => 'K14', 'komponen' => 'C09', 'nama' => 'Layar sentuh (digitizer) rusak', 'prioritas' => 'Sedang',
                'penyebab' => 'Benturan, retak, atau gangguan pada lapisan sentuh',
                'solusi' => 'Ganti digitizer atau modul layar',
                'rules' => ['G35' => 0.5, 'G37' => 0.9, 'G39' => 0.5],
                'rekomendasi' => [
                    'Bersihkan layar dan lepas pelindung layar, lalu uji sentuhan lagi.',
                    'Perbarui sistem dan coba restart atau mode aman untuk menyingkirkan masalah perangkat lunak.',
                    'Jika tetap bermasalah, ganti digitizer atau modul layar di service center.',
                ],
            ],
            [
                'kode' => 'K15', 'komponen' => 'C10', 'nama' => 'Port charger kotor atau berkarat', 'prioritas' => 'Rendah',
                'penyebab' => 'Debu, kotoran, atau lembap di dalam port',
                'solusi' => 'Bersihkan port dengan hati-hati',
                'rules' => ['G42' => 0.9, 'G41' => 0.6, 'G40' => 0.5],
                'rekomendasi' => [
                    'Cabut kabel dan matikan HP.',
                    'Bersihkan port dengan sikat gigi lunak atau tusuk gigi kayu tanpa menekan. Jangan memakai benda logam.',
                    'Jika ada cairan, biarkan kering dulu dan jangan mengisi daya. Kalau masih bermasalah, bawa ke service center.',
                ],
            ],
            [
                'kode' => 'K16', 'komponen' => 'C10', 'nama' => 'Port charger rusak atau longgar', 'prioritas' => 'Sedang',
                'penyebab' => 'Pemakaian lama dan kabel sering digoyang',
                'solusi' => 'Ganti modul port charger',
                'rules' => ['G43' => 0.9, 'G40' => 0.7, 'G44' => 0.7],
                'rekomendasi' => [
                    'Coba kabel dan adaptor lain untuk memastikan masalahnya bukan di kabel.',
                    'Hindari menggoyang kabel saat mengisi daya.',
                    'Ganti modul port charger di service center.',
                ],
            ],
            [
                'kode' => 'K17', 'komponen' => 'C11', 'nama' => 'Power supply mati total', 'prioritas' => 'Tinggi',
                'penyebab' => 'Komponen internal PSU rusak atau lonjakan listrik',
                'solusi' => 'Ganti power supply',
                'rules' => ['G45' => 0.8, 'G47' => 0.6, 'G48' => 0.8],
                'rekomendasi' => [
                    'Cabut kabel listrik. Pastikan stopkontak, kabel power, dan saklar di belakang PSU dalam kondisi baik.',
                    'Jangan membuka casing PSU karena ada kapasitor bertegangan tinggi.',
                    'Ganti power supply dengan daya (watt) yang sesuai kebutuhan.',
                ],
            ],
            [
                'kode' => 'K18', 'komponen' => 'C11', 'nama' => 'Daya power supply tidak stabil', 'prioritas' => 'Sedang',
                'penyebab' => 'Kapasitas watt kurang atau komponen PSU menua',
                'solusi' => 'Ganti dengan power supply berdaya lebih besar',
                'rules' => ['G46' => 0.85, 'G49' => 0.6, 'G45' => 0.4],
                'rekomendasi' => [
                    'Hitung total kebutuhan daya komponen dan bandingkan dengan kapasitas PSU.',
                    'Bersihkan debu pada ventilasi PSU tanpa membuka casing-nya.',
                    'Ganti PSU dengan watt yang memadai dan sertifikasi 80 PLUS.',
                ],
            ],
            [
                'kode' => 'K19', 'komponen' => 'C12', 'nama' => 'Harddisk bad sector', 'prioritas' => 'Tinggi',
                'penyebab' => 'Permukaan disk rusak, mati listrik mendadak, atau usia pakai',
                'solusi' => 'Cadangkan data lalu ganti disk',
                'rules' => ['G50' => 0.6, 'G52' => 0.8, 'G54' => 0.8],
                'rekomendasi' => [
                    'Segera cadangkan data penting ke penyimpanan lain.',
                    'Cek kondisi disk dengan aplikasi pemeriksa SMART seperti CrystalDiskInfo.',
                    'Ganti disk (sebaiknya ke SSD), lalu pasang ulang sistem operasi.',
                ],
            ],
            [
                'kode' => 'K20', 'komponen' => 'C12', 'nama' => 'Harddisk rusak total', 'prioritas' => 'Tinggi',
                'penyebab' => 'Komponen mekanis rusak atau terkena benturan',
                'solusi' => 'Ganti harddisk dan pasang ulang sistem',
                'rules' => ['G51' => 0.85, 'G53' => 0.8, 'G50' => 0.4],
                'rekomendasi' => [
                    'Matikan komputer dan jangan menyalakannya berulang agar kerusakan tidak bertambah.',
                    'Jika datanya penting, bawa ke jasa pemulihan data.',
                    'Ganti harddisk dan pasang ulang sistem operasi.',
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