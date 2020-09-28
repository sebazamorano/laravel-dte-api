<?php

namespace App\Http\Controllers\API\V1;

use App\File;
use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;

class ArchivoAPIController extends AppBaseController
{
    /**
     * Get a link of the S3 object
     * GET|HEAD archivos/{archivo}/empresa/{empresa_id}/descargar.
     *
     * @param File $archivo
     * @return Response
     */
    public function descargar(File $archivo, Empresa $empresa_id)
    {

        $url = $archivo->obtenerLinkTemporal();

        return $this->sendResponse(['url'=>$url], 'Archivo listo para descargar');
    }
}
