<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            EmployeeSeeder::class,
            CategorySeeder::class,
            SupplierSeeder::class,
            ProductSeeder::class,
            ExpenseSeeder::class,
            SalarySeeder::class,
        ]);
    }
}
