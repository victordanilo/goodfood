<?php

namespace App\Policies;

use Auth;
use App\Company;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyPolicy
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
     * @param  \App\User || \App\Company $user
     * @return mixed
     */
    public function create($user)
    {
        return $user->hasPermissionTo('company.create', $this->guardName);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User || \App\Company $user
     * @param  \App\Company  $company
     * @return mixed
     */
    public function update($user, Company $company)
    {
        return $user->id == $company->id || $user->hasPermissionTo('company.update', $this->guardName);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User || \App\Company $user
     * @param  \App\Company  $company
     * @return mixed
     */
    public function delete($user, Company $company)
    {
        return $user->hasPermissionTo('company.delete', $this->guardName);
    }
}
