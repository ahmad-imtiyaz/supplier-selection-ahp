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
                'description' => 'Harga penawaran dari supplier',
                'weight' => 0,
                'is_active' => true
            ],
            [
                'code' => 'C2',
                'name' => 'Kualitas',
                'description' => 'Kualitas produk yang ditawarkan',
                'weight' => 0,
                'is_active' => true
            ],
            [
                'code' => 'C3',
                'name' => 'Waktu Pengiriman',
                'description' => 'Kecepatan dan ketepatan waktu pengiriman',
                'weight' => 0,
                'is_active' => true
            ],
            [
                'code' => 'C4',
                'name' => 'Layanan',
                'description' => 'Kualitas pelayanan supplier',
                'weight' => 0,
                'is_active' => true
            ],
        ];

        foreach ($criterias as $criteria) {
            Criteria::create($criteria);
        }
    }
}
