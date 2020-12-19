<?php

namespace App\Services;

use App\Company;
use MercadoPago\SDK;
use MercadoPago\Payer;
use MercadoPago\Payment;
use MercadoPago\AdvancedPayments\AdvancedPayment;

class PaymentService
{
    private $card;
    private $order;
    private $payer;
    public $disbursements;

    public function __construct(string $order_description, float $order_amount, string $card_token, string $card_method, int $card_installments, $payer)
    {
        SDK::initialize();
        $this->setAccessToken();

        $this->order = (object) [
          'description' => $order_description,
          'amount' => $order_amount,
        ];
        $this->card = (object) [
            'token' => $card_token,
            'method' => $card_method,
            'installments' => $card_installments,
        ];
        $this->payer = $this->payerProcess($payer);
        $this->disbursements = collect();

        return $this;
    }

    public function setAccessToken($accessToken = null)
    {
        $accessToken = $accessToken ?? config('mp.access_token');
        if (empty($accessToken)) {
            throw new \Exception(__('common.access_token_not_defined'));
        }

        SDK::setAccessToken($accessToken);
    }

    public function setDescription(string $description)
    {
        $this->order->description = $description;

        return $this;
    }

    public function setAmount(float $amount)
    {
        $this->order->amount = $amount;

        return $this;
    }

    public function addAmount(float $amount)
    {
        $this->order->amount += $amount;

        return $this;
    }

    public function directProcessing()
    {
        $payment = new Payment;
        $payment->statement_descriptor = config('mp.statement_descriptor');
        $payment->description = $this->order->description;
        $payment->transaction_amount = $this->order->amount;
        $payment->payment_method_id = $this->card->method;
        $payment->installments = $this->card->installments;
        $payment->token = $this->card->token;
        $payment->payer = $this->payer;
        $payment->save();

        return $payment;
    }

    public function marketPlaceProcessing()
    {
        $payment = new AdvancedPayment();
        $payment->payments = [[
            'statement_descriptor' => config('mp.statement_descriptor'),
            'description' => $this->order->description,
            'transaction_amount' => $this->order->amount,
            'payment_method_id' => $this->card->method,
            'installments' => $this->card->installments,
            'token' => $this->card->token,
            'processing_mode' => 'aggregator',
        ]];
        $payment->payer = $this->payer;
        $payment->disbursements = $this->disbursements;
        $payment->save();

        return $payment;
    }

    public function addDisbursement(float $amount, Company $seller)
    {
        $collector_id = ! empty($seller->credential) ? $seller->credential->mp_user_id : null;
        if (empty($collector_id)) {
            throw new \Exception(__('common.seller_without_credential'));
        }

        $this->addAmount($amount);
        $this->disbursements->push([
            'collector_id' => $collector_id,
            'external_reference' => $seller->uuid,
            'amount' => $amount,
            'application_fee' => config('mp.application_fee'),
            'money_release_days' => config('mp.money_release_days'),
        ]);

        return $this;
    }

    private function payerProcess($user)
    {
        $full_name = explode(' ', $user->name);
        $payer = new Payer;
        $payer->first_name = reset($full_name);
        $payer->last_name = (count($full_name) > 1) ? implode(' ', array_slice($full_name, 1)) : '';
        $payer->email = $user->email;
        $payer->identification = [
            'type' => 'CPF',
            'number' => $user->cpf_cnpj,
        ];

        return $payer;
    }
}
