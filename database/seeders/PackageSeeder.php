<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'package_name' => 'Cuci Setrika Reguler',
                'description' => 'Paket favorit keluarga dengan selesai maksimal 48 jam. Sudah termasuk lipat rapi dan parfum premium.',
                'price_per_kg' => 8000,
                'billing_type' => 'per_kg',
                'turnaround_hours' => 48,
            ],
            [
                'package_name' => 'Express 24 Jam',
                'description' => 'Cocok untuk pekerja kantoran. Jemput pagi, malam sudah siap antar.',
                'price_per_kg' => 12000,
                'billing_type' => 'per_kg',
                'turnaround_hours' => 24,
            ],
            [
                'package_name' => 'Kilat 6 Jam',
                'description' => 'Penanganan prioritas hanya untuk pakaian penting. Maksimal 5 kg per order.',
                'price_per_kg' => 18000,
                'billing_type' => 'per_kg',
                'turnaround_hours' => 6,
            ],
            [
                'package_name' => 'Laundry Sepatu Premium',
                'description' => 'Deep cleaning untuk sneakers dan sepatu kantor termasuk finishing anti air ringan.',
                'price_per_kg' => 40000,
                'billing_type' => 'per_item',
                'turnaround_hours' => 72,
            ],
            [
                'package_name' => 'Paket Sprei & Bedcover',
                'description' => 'Cuci setrika khusus bahan tebal dengan mesin kapasitas besar agar tidak kusut.',
                'price_per_kg' => 10000,
                'billing_type' => 'paket',
                'turnaround_hours' => 72,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['package_name' => $package['package_name']],
                $package
            );
        }
    }
}
