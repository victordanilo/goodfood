<?php

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
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(ProductCategorySeeder::class);
        $this->call(OrderStatusSeeder::class);
        $this->call(PaymentStatusSeeder::class);
        $this->call(PaymentMethodSeeder::class);
    }
}
