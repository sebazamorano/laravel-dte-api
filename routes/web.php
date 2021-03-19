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
    Route::get('certificacion/{company}/boleta-exenta', 'CertificacionController@boletaExentaForm')->name('certificacion.boleta_exenta_form');
    Route::post('certificacion/{company}/boleta-exenta', 'CertificacionController@boletaExentaProcesar')->name('certificacion.boleta_exenta_procesar');
    Route::get('certificacion/{company}/boleta-afecta', 'CertificacionController@boletaAfectaForm')->name('certificacion.boleta_afecta_form');
    Route::post('certificacion/{company}/boleta-afecta', 'CertificacionController@boletaAfectaProcesar')->name('certificacion.boleta_afecta_procesar');
    Route::get('certificacion/{company}', 'CertificacionController@index')->name('certificacion.index');
    Route::get('companies/{company}/cafs/subir', 'CafController@subir')->name('cafs.subir');
    Route::post('companies/{company}/cafs/subir', 'CafController@upload')->name('cafs.upload');
    Route::get('companies/{company}/certificadosDigitales/subir', 'CertificadoDigitalController@subir')->name('certificadosDigitales.subir');
    Route::post('companies/{company}/certificadosDigitales/subir', 'CertificadoDigitalController@upload')->name('certificadosDigitales.upload');
    Route::patch('companies/{company}/cafs/{caf}/enUso', 'CafController@setearEnUso')->name('cafs.setInUse');
    Route::patch('companies/{company}/certificadosDigitales/{certificadoDigital}/enUso', 'CertificadoDigitalController@setearEnUso')->name('certificadosDigitales.setInUse');
    Route::get('companies/{company}/documentosAutorizados', 'DocumentoAutorizadoController@edit')->name('documentosAutorizados.edit');
    Route::patch('companies/{company}/documentosAutorizados', 'DocumentoAutorizadoController@update')->name('documentosAutorizados.update');
    Route::resource('companies', 'EmpresaController');

    Route::Resource('companies.branchOffices', 'SucursalController')
        ->parameters(
            ['branchOffices' => 'companyBranchOffices']
        );
});
