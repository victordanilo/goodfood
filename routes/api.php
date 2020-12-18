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
});
