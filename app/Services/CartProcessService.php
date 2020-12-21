<?php

namespace App\Services;

use App\Product;

class CartProcessService
{
    public $amount;
    public $companies;
    public $productByCompany;

    public function __construct(array $products)
    {
        $this->amount = [];
        $this->companies = [];
        $this->productByCompany = [];

        $this->loadProducts($products);
    }

    public function loadProducts(array $products)
    {
        foreach ($products as $item) {
            $product = Product::with('company')->find($item['uuid']);
            if (empty($product) || $product->stock < $item['qty']) {
                throw new \Exception(__('common.product_unavailable', ['product' => $product->description]));
            }

            $this->companies[$product->company_uuid] = $product->company;
            $this->productByCompany[$product->company_uuid][] = (object) array_merge($item, ['product' => $product]);

            $this->addAmount($item['qty'], $product->price, $product->company_uuid);
        }
    }

    public function addAmount(int $qty, float $price, string $company_uuid)
    {
        if (! array_key_exists($company_uuid, $this->amount)) {
            $this->amount[$company_uuid] = 0.00;
        }

        $this->amount[$company_uuid] += (float) number_format(($price * $qty), 2);
    }
}
