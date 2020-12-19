<?php

namespace App\Providers;

use App\User;
use App\Company;
use App\Customer;
use App\ProductCategory;
use App\Policies\UserPolicy;
use Laravel\Passport\Passport;
use App\Policies\CompanyPolicy;
use App\Policies\CustomerPolicy;
use Illuminate\Support\Facades\Gate;
use App\Policies\ProductCategoryPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
        User::class => UserPolicy::class,
        Company::class => CompanyPolicy::class,
        Customer::class => CustomerPolicy::class,
        ProductCategory::class => ProductCategoryPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::tokensCan([
            'customer' => 'The place only customer can access',
            'company' => 'The place only company can access',
            'admin' => 'The place only admin can access',
        ]);
        Passport::routes();
    }
}
