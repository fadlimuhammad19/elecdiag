<?php

namespace App\Services;

use App\Models\Kerusakan;

class CertaintyFactorService
{
    /**
     * @param array    $gejalaUser  format: [gejala_id => cf_user], misal [1 => 0.8, 2 => 1.0]
     * @param int|null $komponenId  batasi hasil ke satu komponen (opsional)
     * @return array daftar kerusakan + nilai CF, urut dari yang tertinggi
     */
    public function hitung(array $gejalaUser, ?int $komponenId = null): array
    {
        $hasil = [];

        $kerusakans = Kerusakan::with('rules')
            ->when($komponenId, fn ($q) => $q->where('komponen_id', $komponenId))
            ->get();

        foreach ($kerusakans as $kerusakan) {
            $cfCombine = null;

            foreach ($kerusakan->rules as $rule) {
                if (!isset($gejalaUser[$rule->gejala_id])) {
                    continue;
                }

                $cf = $rule->cf_pakar * $gejalaUser[$rule->gejala_id];

                $cfCombine = $cfCombine === null
                    ? $cf
                    : $cfCombine + $cf * (1 - $cfCombine);
            }

            if ($cfCombine !== null) {
                $hasil[] = [
                    'kerusakan_id' => $kerusakan->id,
                    'kode'         => $kerusakan->kode,
                    'nama'         => $kerusakan->nama,
                    'solusi'       => $kerusakan->solusi,
                    'cf'           => round($cfCombine, 4),
                    'persen'       => round($cfCombine * 100, 2),
                ];
            }
        }

        usort($hasil, fn ($a, $b) => $b['cf'] <=> $a['cf']);

        return $hasil;
    }
}