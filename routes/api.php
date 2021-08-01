<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('user', 'V1\UserAPIController@show');
});

Route::group(['prefix' => 'v1'], function () {
    Route::get('consulta/pdf', 'V1\ConsultaAPIController@pdf')->name('consulta.pdf');
    Route::get('statistics', 'V1\StatisticAPIController@index');
    //Route::get('test', 'V1\TestAPIController@test');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'V1\AuthAPIController@login');
        Route::post('signup', 'V1\AuthAPIController@signup');

        Route::group(['middleware' => 'auth:api'], function() {
            Route::post('logout', 'V1\AuthAPIController@logout');
            Route::get('user', 'V1\AuthAPIController@user');
        });
    });

    Route::post('notificaciones/correo', 'V1\NotificacionAPIController@correoRecepcionado');

    Route::group(['middleware' => ['auth:api', 'permission']], function () {

        Route::get('archivos/{archivo}/empresa/{empresa_id}/descargar', 'V1\ArchivoAPIController@descargar')->name('archivos.descargar');
        Route::get('users/findUsers', 'V1\UserAPIController@findUsers');
        Route::apiResource('impuestos', 'V1\ImpuestoAPIController');

        Route::apiResource('tiposDocumentos', 'V1\TipoDocumentoAPIController');

        Route::apiResource('actividadesEconomicas', 'V1\ActividadEconomicaAPIController');

        Route::apiResource('mediosPagos', 'V1\MedioPagoAPIController');

        Route::apiResource('regiones', 'V1\RegionAPIController');

        Route::apiResource('provincias', 'V1\ProvinciaAPIController');

        Route::apiResource('comunas', 'V1\ComunaAPIController');

        Route::apiResource('documentosAutorizados', 'V1\DocumentoAutorizadoAPIController');

        Route::apiResource('contribuyentes', 'V1\ContribuyenteAPIController');

        Route::apiResource('bancos', 'V1\BancoAPIController');

        Route::apiResource('unidadesMedidas', 'V1\UnidadMedidaAPIController');

        Route::get('permissions', 'V1\PermissionAPIController@index')->name('permissions.index');

        Route::post('empresas/{empresa_id}/uploadLogo', 'V1\EmpresaAPIController@subirLogo')->name('empresas.subirLogo');
        Route::apiResource('empresas', 'V1\EmpresaAPIController')->parameters(['empresas' => 'empresa_id']);
        Route::apiResource('empresas.roles', 'V1\CompanyRoleAPIController')->parameters(['empresas' => 'empresa_id']);
        Route::apiResource('empresas.users', 'V1\CompanyUserAPIController')->parameters(['empresas' => 'empresa_id']);
        Route::apiResource('empresas.branch_offices', 'V1\SucursalAPIController')->parameters(['empresas' => 'empresa_id']);
        Route::apiResource('empresas.certificados', 'V1\CertificadoEmpresaAPIController')->parameters(['empresas' => 'empresa_id']);
        Route::apiResource('empresas.cafs', 'V1\CafAPIController')->parameters(['empresas' => 'empresa_id']);
        Route::apiResource('empresas.parametros', 'V1\EmpresaParametroAPIController')->parameters(['empresas' => 'empresa_id']);
        Route::apiResource('empresas.actividadesEconomicasEmpresas', 'V1\ActividadEconomicaEmpresaAPIController')->parameters(['empresas' => 'empresa_id']);


        Route::get('documentos/empresa/{empresa_id}', 'V1\DocumentoAPIController@index')->name('documentos.index');
        Route::get('documentos/{documento}/empresa/{empresa_id}', 'V1\DocumentoAPIController@show')->name('documentos.show');
        Route::post('documentos/empresa/{empresa_id}', 'V1\DocumentoAPIController@store')->name('documentos.store');
        Route::put('documentos/{documento}/empresa/{empresa_id}', 'V1\DocumentoAPIController@update')->name('documentos.update');
        Route::delete('documentos/{documento}/empresa/{empresa_id}', 'V1\DocumentoAPIController@destroy')->name('documentos.destroy');
        Route::get('documentos/{id}/empresa/{empresa_id}/sendPDF', 'V1\DocumentoAPIController@sendPDF')->name('documentos.sendPDF');
        Route::get('documentos/{id}/empresa/{empresa_id}/pdf', 'V1\DocumentoAPIController@pdf')->name('documentos.pdf');
        Route::get('documentos/{id}/empresa/{empresa_id}/xml', 'V1\DocumentoAPIController@xml')->name('documentos.xml');
        Route::get('documentos/{id}/empresa/{empresa_id}/xmlEnvio', 'V1\DocumentoAPIController@xmlEnvio')->name('documentos.xml_envio');
        Route::get('documentos/empresa/{empresa_id}/documento/{documento}/consutar-estado-sii', 'V1\DocumentoAPIController@consultarEstadoSii')->name('documentos.consultar_estado_sii');

        Route::get('tickets/{id}/empresa/{empresa_id}/sendPDF', 'V1\DocumentoAPIController@sendPDF')->name('tickets.sendPDF');
        Route::get('tickets/{id}/empresa/{empresa_id}/pdf', 'V1\DocumentoAPIController@pdf')->name('tickets.pdf');
        Route::get('tickets/{id}/empresa/{empresa_id}', 'V1\DocumentoAPIController@show')->name('tickets.show');
        Route::get('tickets/empresa/{empresa_id}', 'V1\DocumentoAPIController@index')->name('tickets.index');
        Route::post('tickets/empresa/{empresa_id}', 'V1\DocumentoAPIController@storeTicket')->name('tickets.store');

        Route::get('received_documents/company/{empresa_id}', 'V1\DetalleRespuestaDteXmlAPIController@index')->name('received_documents.index');
        Route::get('received_documents/{id}/company/{empresa_id}', 'V1\DetalleRespuestaDteXmlAPIController@show')->name('received_documents.show');
        Route::post('received_documents/company/{empresa_id}', 'V1\DetalleRespuestaDteXmlAPIController@store')->name('received_documents.store');
        Route::put('received_documents/{id}/company/{empresa_id}', 'V1\DetalleRespuestaDteXmlAPIController@update')->name('received_documents.update');
        Route::delete('received_documents/{id}/company/{empresa_id}', 'V1\DetalleRespuestaDteXmlAPIController@destroy')->name('received_documents.destroy');

        /*
        Route::resource('documentosEmisor', 'DocumentoEmisorAPIController');

        Route::resource('documentosReceptor', 'DocumentoReceptorAPIController');

        Route::resource('documentosTotales', 'DocumentoTotalesAPIController');

        Route::resource('documentosDetalles', 'DocumentoDetalleAPIController');

        Route::resource('documentoIdDoc', 'DocumentoIddocAPIController');

        Route::resource('documentosReferencias', 'DocumentoReferenciaAPIController');

        Route::resource('documentosDscRcgs', 'DocumentoDscRcgAPIController');

        Route::resource('documentosTransportes', 'DocumentoTransporteAPIController');
        */

        //Route::resource('documentos_actividades_economicas', 'DocumentoActividadEconomicaAPIController');

        Route::apiResource('enviosDtes', 'V1\EnvioDteAPIController');

        Route::apiResource('emails', 'V1\EmailAPIController');

        Route::apiResource('estadistica_envios', 'V1\EstadisticaEnvioAPIController');

        Route::apiResource('envio_dte_errors', 'V1\EnvioDteErrorAPIController');

        Route::apiResource('envio_dte_revisions', 'V1\EnvioDteRevisionAPIController');

        Route::apiResource('envio_dte_revision_detalles', 'V1\EnvioDteRevisionDetalleAPIController');

        Route::apiResource('respuesta_dte_xmls', 'V1\RespuestaDteXmlAPIController');

        Route::get('ticket_credit_notes/company/{empresa_id}', 'V1\TicketCreditNoteAPIController@index')->name('ticketCreditNotes.index');
        Route::get('ticket_credit_notes/{id}/company/{empresa_id}', 'V1\TicketCreditNoteAPIController@show')->name('ticketCreditNotes.show');
        Route::post('ticket_credit_notes/company/{empresa_id}', 'V1\TicketCreditNoteAPIController@store')->name('ticketCreditNotes.store');
        Route::put('ticket_credit_notes/{id}/company/{empresa_id}', 'V1\TicketCreditNoteAPIController@store')->name('ticketCreditNotes.update');
        Route::delete('ticket_credit_notes/{id}/company/{empresa_id}', 'V1\TicketCreditNoteAPIController@destroy')->name('ticketCreditNotes.destroy');

        Route::get('sii/obtenerDtesRecibidos/empresa/{empresa_id}', 'V1\SiiAPIController@obtenerDtesRecibidos')->name('sii.obtener_dtes_recibidos');
        Route::get('sii/timbraje/consulta/empresa/{empresa_id}', 'V1\SiiAPIController@consultarFoliosAutorizadosTimbraje')->name('sii.consultar_folios_autorizados_timbraje');
        Route::post('sii/timbraje/empresa/{empresa_id}', 'V1\SiiAPIController@solicitarTimbraje')->name('sii.solicitar_timbraje');
        Route::get('sii/rcv/{operacion}/{tipo}/empresa/{empresa_id}/{periodo}/{estado}', 'V1\SiiAPIController@obtenerRcv')->name('sii.obtener_rcv');

        Route::get('acceptance_claims/company/{empresa_id}', 'V1\AcceptanceClaimAPIController@index')->name('acceptance_claims.index');
        Route::get('acceptance_claims/{id}/company/{empresa_id}', 'V1\AcceptanceClaimAPIController@show')->name('acceptance_claims.show');
        Route::post('acceptance_claims/company/{empresa_id}', 'V1\AcceptanceClaimAPIController@store')->name('acceptance_claims.store');
        Route::put('acceptance_claims/{id}/company/{empresa_id}', 'V1\AcceptanceClaimAPIController@update')->name('acceptance_claims.update');
        Route::delete('acceptance_claims/{id}/company/{empresa_id}', 'V1\AcceptanceClaimAPIController@destroy')->name('acceptance_claims.destroy');
    });

});
