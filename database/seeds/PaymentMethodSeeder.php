<?php

use App\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PaymentMethod::create(['id' => PaymentMethod::CARD,   'name' => 'Cartão']);
        PaymentMethod::create(['id' => PaymentMethod::CASH,   'name' => 'Dinheiro']);
        PaymentMethod::create(['id' => PaymentMethod::TICKET, 'name' => 'Boleto']);
    }
}
