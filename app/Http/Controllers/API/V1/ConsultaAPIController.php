<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\AppBaseController;
use App\Models\Documento;
use App\Models\Empresa;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Http\Request;

class ConsultaAPIController extends AppBaseController
{
    public function pdf(Request $request)
    {

        $validatedData = $request->validate([
            'rut' => 'required|cl_rut',
            'folio' => 'required|integer',
            'monto' => 'required|integer'
        ]);

        $rut = Rut::parse($request->input('rut'))->format(Rut::FORMAT_WITH_DASH);

        $empresa = Empresa::where('rut',  $rut)->first();

        if (empty($empresa)){
            if(isset($request->html_response)){
                abort(404);
            }

            return $this->sendError('Empresa no encontrada en nuestros registros', 404);
        }

        $documento = Documento::buscar($request, $empresa->id)->first();

        if(!$documento){
            if(isset($request->html_response)){
                abort(404);
            }

            return $this->sendError('Documento no encontrado en nuestros registros', 404);
        }


        $pdf = $documento->obtenerPdfString("", 1);

        if($request->filled('responseType') && $request->input('responseType') == 'base64'){
            return $this->sendResponse(['file' => base64_encode($pdf)], 'PDF Obtenido');
        }else{
            return new \Illuminate\Http\Response($pdf, 200, array(
                'Content-Type' => 'application/pdf',
                'X-Vapor-Base64-Encode' => 'True',
                'Content-Disposition' =>  'attachment; filename="document.pdf"',
                'Content-Length' => strlen($pdf),
            ));
        }

    }
}
