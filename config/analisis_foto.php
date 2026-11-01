<?php

return [
    // Kelas baru dianggap terdeteksi kalau keyakinannya mencapai angka ini (0-1)
    'ambang' => 0.7,

    // Nama kelas di Teachable Machine => kode gejala yang diisi "Ya"
    // Kelas yang tidak tercantum (Normal, Lainnya) diabaikan
    'pemetaan' => [
        'Kapasitor Kembung' => ['G01'],
        'Resistor Gosong'   => ['G04'],
        'Baterai Kembung'   => ['G33'],
        'Layar Retak'       => ['G35'],
    ],
];