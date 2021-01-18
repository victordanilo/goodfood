<?php

use App\OrderStatus;
use Illuminate\Database\Seeder;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OrderStatus::create(['id' => OrderStatus::PENDING,  'name' => 'Pendente']);
        OrderStatus::create(['id' => OrderStatus::APPROVED, 'name' => 'Aprovado']);
        OrderStatus::create(['id' => OrderStatus::COMPLETE, 'name' => 'Completo']);
        OrderStatus::create(['id' => OrderStatus::RETURNED, 'name' => 'Devolvido']);
        OrderStatus::create(['id' => OrderStatus::CANCELED, 'name' => 'Cancelado']);
    }
}
