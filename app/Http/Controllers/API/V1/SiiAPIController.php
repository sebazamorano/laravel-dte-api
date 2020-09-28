<?php

namespace App\Http\Controllers\API\V1;

use App\Components\CAF;
use App\Components\Sii;
use App\Http\Request\APIRequest;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AppBaseController;

class SiiAPIController extends AppBaseController
{
    public function obtenerDtesRecibidos(Request $request)
    {
        $empresa = Empresa::find($request->input('empresa_id'));
        $siiComponent = new Sii($empresa);

        $file_path = $siiComponent->recuperarDtesRecibidos($request->input('desde'), $request->input('hasta'));
        $archivoTemporal = tmpfile();
        $path = stream_get_meta_data($archivoTemporal)['uri'];
        fwrite($archivoTemporal, Storage::get($file_path));
        Storage::delete($file_path);

        header('Content-type: application/pdf');
        header('Content-Length: '.filesize($path));
        readfile($path);
    }

    public function obtenerRcv(Request $request, $operacion, $tipo, $empresa_id, $periodo, $estado)
    {
        $data = [
            'operacion' => $operacion,
            'estadoContab' => $estado,
            'ptributario' => $periodo,
            'tipo' => $tipo,
        ];

        $empresa = Empresa::find($empresa_id);
        $siiComponent = new Sii($empresa);

        if($request->filled('ambiente')){
            $siiComponent->setAmbiente('palena');
        }
        $rcv = $siiComponent->getRCVDetail($data);

        return response()->json(json_decode($rcv)->data, '200');
    }

    public function consultarFoliosAutorizadosTimbraje(APIRequest $request, $empresa_id)
    {
        $empresa = Empresa::find($empresa_id);
        $caf = CAF::verificarMaximoAutorizado($request->input('tipo_dte'), $empresa);
        return $this->sendResponse($caf, 'Consulta realizada correctamente');
    }

    public function solicitarTimbraje(APIRequest $request, $empresa_id)
    {
        $empresa = Empresa::find($empresa_id);
        $caf = CAF::timbraje($request->input('tipo_dte'), $empresa, $request->input('cantidad'));

        return $this->sendResponse($caf, 'Consulta realizada correctamente');
    }
}
