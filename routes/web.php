<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//ADMINISTRADOR

Auth::routes();
//Auth::routes(['register' => false]);
//Auth::routes(['verify' => true]);

Route::get('/', 'HomeController@index')->name('home');


Route::namespace('Admin')->prefix('admin')->middleware(
    'auth'
    // ,'verified'
)->name('admin.')->group(
    function () {
        Route::get('/', 'adminController@administrador')->name('principal');
        Route::resource('/users', 'adminController');
        Route::put('/users/updateRoles/{id}', 'adminController@updateUserRoles')->name('updateUserRoles');
        Route::impersonate();

        Route::namespace('Materiales')->prefix('materiales')->name('materiales.')->group(function () {
            Route::get('/', 'MaterialesController@index');
            Route::post('/create', 'MaterialesController@create')->name('create');
            Route::put('/edit/{id}', 'MaterialesController@edit')->name('edit');
            Route::delete('/destroy/{id}', 'MaterialesController@destroy')->name('destroy');
        });

        Route::namespace('Productos')->prefix('productos')->name('productos.')->group(function () {
            Route::get('/', 'ProductosController@index');
            Route::post('/create', 'ProductosController@create')->name('create');
            Route::get('/details/{id}', 'ProductosController@details')->name('details');
            Route::put('/edit/{id}', 'ProductosController@edit')->name('edit');
            Route::put('/editAsignacionMateriales/{id}', 'ProductosController@editAsignacionMateriales')->name('editAsignacionMateriales');
            Route::put('/editCantidadMateriales/{id}', 'ProductosController@editCantidadMateriales')->name('editCantidadMateriales');
            Route::delete('/destroy/{id}', 'ProductosController@destroy')->name('destroy');
        });

        Route::namespace('Configuraciones')->prefix('configuraciones')->name('configuraciones.')->group(function () {
            Route::put('/', 'ConfiguracionesController@updateSueldoBase')->name('updateSueldoBase');
        });

        Route::prefix('roles')->name('roles.')->group(function () {
            Route::get('/', 'RolesController@roles')->name('roles');
            Route::post('/newRole', 'RolesController@newRole')->name('newRole');
            Route::put('/deleteRole/{id}', 'RolesController@deleteRole')->name('deleteRole');

            Route::prefix('role')->name('role.')->group(function () {
                Route::get('/{requirement}', 'RolesController@showRole')->name('show');
                Route::put('/updateRole/{id}', 'RolesController@updateRole')->name('updateRole');
            });
        });
    }
);
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
