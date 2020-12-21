<?php

use App\PaymentStatus;
use Illuminate\Database\Seeder;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentStatus::create(['id' => PaymentStatus::PENDING,  'name' => 'Pendente']);
        PaymentStatus::create(['id' => PaymentStatus::APPROVED, 'name' => 'Aprovado']);
        PaymentStatus::create(['id' => PaymentStatus::COMPLETE, 'name' => 'Completo']);
        PaymentStatus::create(['id' => PaymentStatus::REFUND,   'name' => 'Estornado']);
        PaymentStatus::create(['id' => PaymentStatus::CANCELED, 'name' => 'Cancelado']);
    }
}
