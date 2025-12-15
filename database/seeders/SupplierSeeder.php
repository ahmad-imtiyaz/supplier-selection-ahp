<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP001',
                'name' => 'PT Sumber Makmur',
                'address' => 'Jl. Industri Raya No. 12, Semarang',
                'phone' => '081234567801',
                'email' => 'sumbermakmur@vendor.co.id',
                'contact_person' => 'Andi Pratama',
                'description' => 'Supplier bahan baku utama dengan pengalaman kerja sama lebih dari 5 tahun dan pengiriman yang relatif stabil.',
                'is_active' => true
            ],
            [
                'code' => 'SUP002',
                'name' => 'CV Mitra Sejahtera',
                'address' => 'Jl. Ahmad Yani No. 45, Solo',
                'phone' => '081234567802',
                'email' => 'mitrasejahtera@vendor.co.id',
                'contact_person' => 'Rina Kurniawati',
                'description' => 'Supplier dengan harga kompetitif dan kualitas produk yang cukup konsisten untuk kebutuhan produksi rutin.',
                'is_active' => true
            ],
            [
                'code' => 'SUP003',
                'name' => 'PT Global Supply Nusantara',
                'address' => 'Jl. Raya Surabaya No. 88, Surabaya',
                'phone' => '081234567803',
                'email' => 'globalsupply@vendor.co.id',
                'contact_person' => 'Budi Santoso',
                'description' => 'Supplier skala nasional dengan jaringan distribusi luas dan kemampuan pengiriman dalam jumlah besar.',
                'is_active' => true
            ],
            [
                'code' => 'SUP004',
                'name' => 'CV Anugerah Jaya',
                'address' => 'Jl. Diponegoro No. 23, Kudus',
                'phone' => '081234567804',
                'email' => 'anugerahjaya@vendor.co.id',
                'contact_person' => 'Dwi Hartanto',
                'description' => 'Supplier yang saat ini tidak aktif karena performa pengiriman yang kurang konsisten dalam periode evaluasi terakhir.',
                'is_active' => false // NON-AKTIF
            ],
            [
                'code' => 'SUP005',
                'name' => 'PT Prima Logistic',
                'address' => 'Jl. Gatot Subroto No. 101, Jakarta',
                'phone' => '081234567805',
                'email' => 'primalogistic@vendor.co.id',
                'contact_person' => 'Sari Puspitasari',
                'description' => 'Supplier yang sedang dalam tahap evaluasi ulang dan belum digunakan dalam proses pengadaan saat ini.',
                'is_active' => false // NON-AKTIF
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::updateOrCreate(
                ['code' => $supplier['code']], // Cek berdasarkan code
                $supplier // Data yang akan di-insert/update
            );
        }

        $this->command->info('✅ 5 Supplier berhasil di-seed (3 AKTIF, 2 NON-AKTIF)');
    }
}
