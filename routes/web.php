<?php

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
Auth::routes(['register' => false]);
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/', 'HomeController@welcome')->name('welcome');
Route::get('/consulta', 'ConsultaController@index')->name('consulta');

Route::middleware(['auth', 'isSuperAdmin'])->group(function () {
    Route::get('companies/{company}/documentosAutorizados', 'DocumentoAutorizadoController@edit')->name('documentosAutorizados.edit');
    Route::patch('companies/{company}/documentosAutorizados', 'DocumentoAutorizadoController@update')->name('documentosAutorizados.update');
    Route::resource('companies', 'EmpresaController');

    Route::Resource('companies.branchOffices', 'SucursalController')
        ->parameters(
            ['branchOffices' => 'companyBranchOffices']
        );
});
