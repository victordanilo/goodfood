<?php

namespace App\Policies;

use Auth;
use App\Customer;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
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
     * @param  \App\User || \App\Customer $user
     * @return mixed
     */
    public function create($user)
    {
        return $user->hasPermissionTo('customer.create', $this->guardName);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User || \App\Customer $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function update($user, Customer $customer)
    {
        return $user->id == $customer->id || $user->hasPermissionTo('customer.update', $this->guardName);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User || \App\Customer $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function delete($user, Customer $customer)
    {
        return $user->hasPermissionTo('customer.delete', $this->guardName);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User || \App\Customer $user
     * @return mixed
     */
    public function createAddress($user, Customer $customer)
    {
        return $user->id == $customer->id || $user->hasPermissionTo('customer.update', $this->guardName);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User || \App\Customer $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function updateAddress($user, Customer $customer)
    {
        return $user->id == $customer->id || $user->hasPermissionTo('customer.update', $this->guardName);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User || \App\Customer $user
     * @param  \App\Customer  $customer
     * @return mixed
     */
    public function deleteAddress($user, Customer $customer)
    {
        return $user->id == $customer->id || $user->hasPermissionTo('customer.update', $this->guardName);
    }
}
