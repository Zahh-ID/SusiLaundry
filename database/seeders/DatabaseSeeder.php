<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            PackageSeeder::class,
            CustomerOrderSeeder::class,
        ]);

        User::updateOrCreate(
            ['email' => 'admin@susilaundry.test'],
            [
                'name' => 'Admin Omah Susi',
                'password' => Hash::make('password'),
            ]
        );
    }
}
