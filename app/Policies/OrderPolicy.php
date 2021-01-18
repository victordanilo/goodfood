<?php

namespace App\Policies;

use Auth;
use App\User;
use App\Order;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    private $guardName;

    public function __construct()
    {
        $this->guardName = Auth::getDefaultDriver();
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Company  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function view($user, Order $order)
    {
        return $user->uuid == $order->company_uuid
            || $user->uuid == $order->customer_uuid
            || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Customer  $user
     * @return mixed
     */
    public function create($user = null)
    {
        return $this->guardName == 'customer';
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Order  $order
     * @return mixed
     */
    public function update($user, Order $order)
    {
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function delete(User $user, Order $order)
    {
        return $user->hasRole('admin') && $this->guardName == 'admin';
    }
}
