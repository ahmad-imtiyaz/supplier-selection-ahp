<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Criteria;

class CriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $criterias = [
            [
                'code' => 'C1',
                'name' => 'Harga',
                'description' => 'Kesesuaian harga yang ditawarkan supplier dengan anggaran perusahaan, termasuk stabilitas harga dan transparansi biaya dalam jangka panjang.',
                'weight' => 0,
                'is_active' => true
            ],
            [
                'code' => 'C2',
                'name' => 'Kualitas Produk',
                'description' => 'Tingkat kesesuaian kualitas produk yang disuplai dengan standar dan spesifikasi yang ditetapkan oleh perusahaan.',
                'weight' => 0,
                'is_active' => true
            ],
            [
                'code' => 'C3',
                'name' => 'Ketepatan Waktu Pengiriman',
                'description' => 'Kemampuan supplier dalam mengirimkan barang sesuai dengan jadwal dan waktu yang telah disepakati.',
                'weight' => 0,
                'is_active' => true
            ],
            [
                'code' => 'C4',
                'name' => 'Kapasitas Produksi',
                'description' => 'Kemampuan supplier dalam memenuhi jumlah permintaan barang dalam skala besar atau mendadak.',
                'weight' => 0,
                'is_active' => false // NON-AKTIF
            ],
            [
                'code' => 'C5',
                'name' => 'Layanan Purna Jual',
                'description' => 'Kualitas layanan supplier setelah proses pengiriman, termasuk penanganan keluhan dan garansi produk.',
                'weight' => 0,
                'is_active' => false // NON-AKTIF
            ],
        ];

        foreach ($criterias as $criteria) {
            Criteria::updateOrCreate(
                ['code' => $criteria['code']], // Cek berdasarkan code
                $criteria // Data yang akan di-insert/update
            );
        }

        $this->command->info('✅ 5 Kriteria berhasil di-seed (3 AKTIF, 2 NON-AKTIF)');
    }
}
