<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'admin', 'middleware' => 'api', 'guard' => 'admin'], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');

        Route::group(['prefix' => 'password'], function () {
            Route::post('create', 'PasswordResetController@create');
            Route::get('find/{token}', 'PasswordResetController@find')->name('admin.password.find');
            Route::post('reset', 'PasswordResetController@reset');
        });

        Route::group(['middleware' => ['auth:admin', 'scope.validate']], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');
        });
    });

    Route::group(['middleware' => ['auth:admin', 'scope.validate']], function () {
        Route::group(['prefix' => 'profile', 'middleware' => ['load_profile_user']], function () {
            Route::put('/', [
                'as' => 'api.admin.profile.update',
                'uses' => 'UserController@update',
            ]);
        });
        Route::group(['prefix' => 'role', 'middleware' => 'role:admin'], function () {
            Route::get('/', [
                'as' => 'api.admin.role.list',
                'uses' => 'RoleController@index',
            ]);

            Route::post('/', [
                'as' => 'api.admin.role.add',
                'uses' => 'RoleController@store',
            ]);

            Route::put('/{role}', [
                'as' => 'api.admin.role.update',
                'uses' => 'RoleController@update',
            ]);

            Route::delete('/{role}', [
                'as' => 'api.admin.role.delete',
                'uses' => 'RoleController@destroy',
            ]);

            Route::get('/{role}/permissions', [
                'as' => 'api.admin.role.get_permissions',
                'uses' => 'RoleController@permissions',
            ]);

            Route::post('/{role}/permissions', [
                'as' => 'api.admin.role.sync_permissions',
                'uses' => 'RoleController@syncPermissions',
            ]);
        });
        Route::group(['prefix' => 'permission', 'middleware' => 'role:admin'], function () {
            Route::get('/', [
                'as' => 'api.admin.permission.list',
                'uses' => 'PermissionController@index',
            ]);

            Route::post('/', [
                'as' => 'api.admin.permission.add',
                'uses' => 'PermissionController@store',
            ]);

            Route::put('/{permission}', [
                'as' => 'api.admin.permission.update',
                'uses' => 'PermissionController@update',
            ]);

            Route::delete('/{permission}', [
                'as' => 'api.admin.permission.delete',
                'uses' => 'PermissionController@destroy',
            ]);
        });
        Route::group(['prefix' => 'user'], function () {
            Route::get('/', [
                'as' => 'api.admin.user.list',
                'uses' => 'UserController@index',
            ]);

            Route::post('/', [
                'as' => 'api.admin.user.add',
                'uses' => 'UserController@store',
                'middleware' => 'role:admin',
            ]);

            Route::get('/{user}', [
                'as' => 'api.admin.user.show',
                'uses' => 'UserController@show',
            ]);

            Route::put('/{user}', [
                'as' => 'api.admin.user.update',
                'uses' => 'UserController@update',
            ]);

            Route::delete('/{user}', [
                'as' => 'api.admin.user.delete',
                'uses' => 'UserController@destroy',
            ]);

            Route::post('/{user}/set-role', [
                'as' => 'api.admin.user.set_role',
                'uses' => 'UserController@setRole',
                'middleware' => 'role:admin',
            ]);
        });
        Route::group(['prefix' => 'company'], function () {
            Route::get('/', [
                'as' => 'api.admin.company.list',
                'uses' => 'CompanyController@index',
            ]);

            Route::post('/', [
                'as' => 'api.admin.company.add',
                'uses' => 'CompanyController@store',
            ]);

            Route::get('/{company}', [
                'as' => 'api.admin.company.show',
                'uses' => 'CompanyController@show',
            ]);

            Route::put('/{company}', [
                'as' => 'api.admin.company.update',
                'uses' => 'CompanyController@update',
            ]);

            Route::delete('/{company}', [
                'as' => 'api.admin.company.delete',
                'uses' => 'CompanyController@destroy',
            ]);
        });
        Route::group(['prefix' => 'customer'], function () {
            Route::get('/', [
                'as' => 'api.admin.customer.list',
                'uses' => 'CustomerController@index',
            ]);

            Route::post('/', [
                'as' => 'api.admin.customer.add',
                'uses' => 'CustomerController@store',
            ]);

            Route::get('/{customer}', [
                'as' => 'api.admin.customer.show',
                'uses' => 'CustomerController@show',
            ]);

            Route::put('/{customer}', [
                'as' => 'api.admin.customer.update',
                'uses' => 'CustomerController@update',
            ]);

            Route::delete('/{customer}', [
                'as' => 'api.admin.customer.delete',
                'uses' => 'CustomerController@destroy',
            ]);

            Route::group(['prefix' => '/{customer}/address'], function () {
                Route::get('/', [
                    'as' => 'api.admin.customer.address.list',
                    'uses' => 'CustomerAddressController@index',
                ]);

                Route::post('/', [
                    'as' => 'api.admin.customer.address.add',
                    'uses' => 'CustomerAddressController@store',
                ]);

                Route::put('/{address}', [
                    'as' => 'api.admin.customer.address.update',
                    'uses' => 'CustomerAddressController@update',
                ]);

                Route::delete('/{address}', [
                    'as' => 'api.admin.customer.address.delete',
                    'uses' => 'CustomerAddressController@destroy',
                ]);
            });
        });
        Route::group(['prefix' => 'product'], function () {
            Route::get('/', [
                'as' => 'api.admin.product.list',
                'uses' => 'ProductController@index',
            ]);

            Route::group(['prefix' => 'category'], function () {
                Route::get('/', [
                    'as' => 'api.admin.product.category.list',
                    'uses' => 'ProductCategoryController@index',
                ]);

                Route::post('/', [
                    'as' => 'api.admin.product.category.add',
                    'uses' => 'ProductCategoryController@store',
                ]);

                Route::put('/{category}', [
                    'as' => 'api.admin.product.category.update',
                    'uses' => 'ProductCategoryController@update',
                ]);

                Route::delete('/{category}', [
                    'as' => 'api.admin.product.category.delete',
                    'uses' => 'ProductCategoryController@destroy',
                ]);
            });
        });
        Route::group(['prefix' => 'order'], function () {
            Route::get('/', [
                'as' => 'api.admin.order.list',
                'uses' => 'OrderController@indexAdmin',
            ]);

            Route::get('/{order}', [
                'as' => 'api.admin.order.show',
                'uses' => 'OrderController@show',
            ]);

            Route::delete('/{order}', [
                'as' => 'api.admin.order.delete',
                'uses' => 'OrderController@destroy',
            ]);
        });

        Route::get('test', 'ApplicationController@test');
    });
});

Route::group(['prefix' => 'manager', 'middleware' => 'api', 'guard' => 'company'], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');

        Route::group(['prefix' => 'password'], function () {
            Route::post('create', 'PasswordResetController@create');
            Route::get('find/{token}', 'PasswordResetController@find')->name('company.password.find');
            Route::post('reset', 'PasswordResetController@reset');
        });

        Route::group(['middleware' => ['auth:company', 'scope.validate']], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');
        });
    });

    Route::group(['middleware' => ['api', 'auth:company', 'scope.validate']], function () {
        Route::group(['prefix' => 'profile', 'middleware' => ['load_profile_company']], function () {
            Route::put('/', [
                'as' => 'api.manager.profile.update',
                'uses' => 'CompanyController@update',
            ]);

            Route::post('/credential', [
                'as' => 'api.manager.profile.create_credential',
                'uses' => 'CompanyController@createCredential',
            ]);
        });
        Route::group(['prefix' => 'product'], function () {
            Route::get('/', [
                'as' => 'api.manager.product.list',
                'uses' => 'ProductController@indexCompany',
            ]);

            Route::post('/', [
                'as' => 'api.manager.product.add',
                'uses' => 'ProductController@store',
            ]);

            Route::put('/{product}', [
                'as' => 'api.manager.product.update',
                'uses' => 'ProductController@update',
            ]);

            Route::delete('/{product}', [
                'as' => 'api.manager.product.delete',
                'uses' => 'ProductController@destroy',
            ]);

            Route::get('/category', [
                'as' => 'api.manager.product.category',
                'uses' => 'ProductCategoryController@index',
            ]);
        });
        Route::group(['prefix' => 'order'], function () {
            Route::get('/', [
                'as' => 'api.manager.order.list',
                'uses' => 'OrderController@index',
            ]);

            Route::get('/{order}', [
                'as' => 'api.manager.order.show',
                'uses' => 'OrderController@show',
            ]);
        });
    });
});

Route::group(['middleware' => 'api', 'guard' => 'customer'], function () {
    Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
        Route::post('login', 'AuthController@login');
        Route::post('signup', 'AuthController@signup');

        Route::group(['prefix' => 'password'], function () {
            Route::post('create', 'PasswordResetController@create');
            Route::get('find/{token}', 'PasswordResetController@find')->name('customer.password.find');
            Route::post('reset', 'PasswordResetController@reset');
        });

        Route::group(['middleware' => ['auth:customer', 'scope.validate']], function () {
            Route::get('logout', 'AuthController@logout');
            Route::get('user', 'AuthController@user');
        });
    });

    Route::group(['middleware' => ['auth:customer', 'scope.validate']], function () {
        Route::group(['prefix' => 'profile', 'middleware' => 'load_profile_customer'], function () {
            Route::put('/', [
                'as' => 'api.profile.update',
                'uses' => 'CustomerController@update',
            ]);

            Route::group(['prefix' => 'address'], function () {
                Route::get('/', [
                    'as' => 'api.profile.address.list',
                    'uses' => 'CustomerAddressController@index',
                ]);

                Route::post('/', [
                    'as' => 'api.profile.address.add',
                    'uses' => 'CustomerAddressController@store',
                ]);

                Route::put('/{address}', [
                    'as' => 'api.profile.address.update',
                    'uses' => 'CustomerAddressController@update',
                ]);

                Route::delete('/{address}', [
                    'as' => 'api.profile.address.delete',
                    'uses' => 'CustomerAddressController@destroy',
                ]);
            });
        });
        Route::group(['prefix' => 'order'], function () {
            Route::get('/', [
                'as' => 'api.order.list',
                'uses' => 'OrderController@index',
            ]);

            Route::post('/', [
                'as' => 'api.order.add',
                'uses' => 'OrderController@store',
            ]);

            Route::get('/{order}', [
                'as' => 'api.order.show',
                'uses' => 'OrderController@show',
            ]);
        });
    });

    Route::group(['prefix' => 'product'], function () {
        Route::get('/', [
            'as' => 'api.product.list',
            'uses' => 'ProductController@index',
        ]);

        Route::get('/category', [
            'as' => 'api.product.category',
            'uses' => 'ProductCategoryController@index',
        ]);
    });

    Route::post('/calculate-delivery', [
        'as' => 'api.delivery.calculate',
        'uses' => 'OrderController@previewDeliveryPrice',
    ]);
});
