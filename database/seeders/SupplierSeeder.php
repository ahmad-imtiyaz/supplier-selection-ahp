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
                'name' => 'PT. Supplier Utama',
                'address' => 'Jl. Raya No. 123, Jakarta',
                'phone' => '021-1234567',
                'email' => 'contact@supplier1.com',
                'contact_person' => 'John Doe',
                'is_active' => true
            ],
            [
                'code' => 'SUP002',
                'name' => 'CV. Mitra Sejahtera',
                'address' => 'Jl. Merdeka No. 456, Bandung',
                'phone' => '022-7654321',
                'email' => 'info@mitra.com',
                'contact_person' => 'Jane Smith',
                'is_active' => true
            ],
            [
                'code' => 'SUP003',
                'name' => 'UD. Berkah Jaya',
                'address' => 'Jl. Gatot Subroto No. 789, Surabaya',
                'phone' => '031-9876543',
                'email' => 'berkah@jaya.com',
                'contact_person' => 'Ahmad Yani',
                'is_active' => true
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
