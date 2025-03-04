<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MenuDetail;
use App\Models\UnitBerat;
use App\Models\MenuGroup;
use Illuminate\Database\Seeder;
use Laravolt\Indonesia\Seeds\CitiesSeeder;
use Laravolt\Indonesia\Seeds\VillagesSeeder;
use Laravolt\Indonesia\Seeds\DistrictsSeeder;
use Laravolt\Indonesia\Seeds\ProvincesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory(5)->create();

        $this->call([
            MenuSeeder::class,
            UnitBeratSeeder::class,
            VillagesSeeder::class,
            DistrictsSeeder::class,
            CitiesSeeder::class,
            ProvincesSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);
    }
}
