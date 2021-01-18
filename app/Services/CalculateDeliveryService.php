<?php

namespace App\Services;

use App\Company;

class CalculateDeliveryService
{
    private $pricePerKm;
    private $companyAddress;
    private $deliveryAddress;
    private $deliveryPrice;

    public function __construct(Company $company, string $address)
    {
        $this->pricePerKm = (float) $company->delivery_cost ?? 0.00;
        $this->companyAddress = $this->parseAddressFromCompany($company);
        $this->deliveryAddress = $address;
        $this->deliveryPrice = (float) 0.00;

        if (! empty($this->companyAddress) && ! empty($this->deliveryAddress)) {
            $this->calculatePrice();
        }
    }

    public function getPrice():float
    {
        return $this->deliveryPrice;
    }

    public function parseAddressFromCompany(Company $company):string
    {
        $address = array_filter([
            $company->street,
            $company->n,
            $company->complement,
            $company->district,
            $company->zip_code,
            $company->city,
            $company->uf,
        ]);

        return implode(',', $address);
    }

    public function calculateDistance():float
    {
        $distance = 0;
        $response = \GoogleMaps::load('distancematrix')
            ->setParam([
                'origins' => $this->companyAddress,
                'destinations' => $this->deliveryAddress,
                'mode' => 'driving',
                'language' => 'pt-BR',
            ])
            ->getResponseByKey('rows.elements')['rows'][0]['elements'][0];

        if ($response['status'] == 'OK') {
            $distance = (((int) $response['distance']['value']) / 1000);
        }

        return $distance;
    }

    public function calculatePrice():void
    {
        $distance = $this->calculateDistance();
        $price_per_km = $this->pricePerKm;

        $this->deliveryPrice = (float) number_format(($distance * $price_per_km), 2);
    }
}
