<?php

namespace App\Policies;

use Auth;
use App\Company;
use App\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    private $guardName;

    public function __construct()
    {
        $this->guardName = Auth::getDefaultDriver();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User || \App\Company  $user
     * @return mixed
     */
    public function create($user)
    {
        return $this->guardName == 'company';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User || \App\Company  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function update($user, Product $product)
    {
        return $user->uuid == $product->company_uuid && $this->guardName == 'company';
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Product  $product
     * @return mixed
     */
    public function delete($user, Product $product)
    {
        return $user->uuid == $product->company_uuid && $this->guardName == 'company';
    }
}
