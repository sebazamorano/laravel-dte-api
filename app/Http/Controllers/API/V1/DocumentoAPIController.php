<?php

namespace App\Http\Controllers\API\V1;

use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Response;
use App\Models\Documento;
use App\Jobs\SendEmailJob;
use Illuminate\Http\Request;
use App\Jobs\ProcesarEnvioDte;
use App\Components\TipoArchivo;
use App\Http\Request\APIRequest;
use App\Components\TipoDocumento;
use Illuminate\Support\Facades\DB;
use App\Repositories\DocumentoRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Resources\DocumentoCollection;
use App\Http\Request\API\CreateBoletaAPIRequest;
use App\Http\Request\API\CreateDocumentoAPIRequest;
use App\Http\Request\API\UpdateDocumentoAPIRequest;
use App\Http\Resources\Documento as DocumentoResource;

/**
 * Class DocumentoController.
 */
class DocumentoAPIController extends AppBaseController
{
    /** @var DocumentoRepository */
    private $documentoRepository;

    public function __construct(DocumentoRepository $documentoRepo)
    {
        $this->documentoRepository = $documentoRepo;
    }

    /**
     * Display a listing of the Documento.
     * GET|HEAD /documentos.
     *
     * @param Request $request
     * @return Response
     */
    public function index(APIRequest $request)
    {
        /* @var Documento $documentos */
        $documentos = Documento::buscar($request);

        if ($request->filled('paginate')) {
            $documentos = $documentos->paginate($request->input('paginate'));
        } else {
            $documentos = $documentos->paginate(10);
        }

        //return new DocumentoCollection($documentos);
        return $this->sendResponse(new DocumentoCollection($documentos), 'Documentos retrieved successfully');
    }

    /**
     * Store a newly created Ticket in storage.
     * POST /documentos.
     *
     * @param CreateBoletaAPIRequest $request
     * @param int empresa_id
     *
     * @return Response
     */
    public function storeTicket(CreateBoletaAPIRequest $request, $empresa_id)
    {
        DB::beginTransaction();
        $input = $request->all();
        /* @var Documento $documentos */
        /* @var Documento $documento */

        try {
            $input = Documento::crearDatosEmisor($input);
            $documentos = $this->documentoRepository->create($input);

            $documento = Documento::find($documentos->id);
            $asignacion = $documento->asignarFolio($request->input('idDoc.Folio', null));

            if (! $asignacion) {
                DB::rollBack();
                return $this->sendError('No existen folios para asignar, o no adjunto el folio, o folio ya fue utilizado');
            }

            if (in_array($documento->tipo_documento_id, TipoDocumento::$tipos_documentos)) {
                $certificado = $documento->asignarCertificado();

                if (! $certificado) {
                    DB::rollBack();

                    return $this->sendError('No se logro asignar certificado');
                }

                $xml_string = $documento->generarDTE();
                $xml = $documento->subirXmlDteS3($xml_string);
                $documento->archivos()->attach($xml->id, ['tipo' => TipoArchivo::DTE]);
            }

            //DB::rollBack();
            DB::commit();

            return $this->sendResponse($documento->toArray(), 'Documento guardado exitosamente.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Store a newly created Documento in storage.
     * POST /documentos.
     *
     * @param CreateDocumentoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentoAPIRequest $request)
    {
        DB::beginTransaction();
        $input = $request->all();
        /* @var Documento $documentos */
        /* @var Documento $documento */

        try {
            $input = Documento::crearDatosEmisor($input);
            $documentos = $this->documentoRepository->create($input);

            /*$documentos = $this->documentoRepository
                ->with(['dscRcgGlobal', 'totales', 'detalle', 'transporte', 'actividadesEconomicas', 'idDoc', 'referencia', 'receptor', 'emisor'])
                ->findWithoutFail($documentos->id);
            */
            $documento = Documento::find($documentos->id);
            $asignacion = $documento->asignarFolio($request->input('idDoc.Folio', null));

            if (! $asignacion) {
                DB::rollBack();

                return $this->sendError('No existen folios para asignar, o no adjunto el folio, o folio ya fue utilizado');
            }

            if (in_array($documento->tipo_documento_id, TipoDocumento::$tipos_documentos)) {
                $certificado = $documento->asignarCertificado();

                if (! $certificado) {
                    DB::rollBack();

                    return $this->sendError('No se logro asignar certificado');
                }

                $xml_string = $documento->generarDTE();
                $mensaje_validacion = '';
                $result = $documento->validarXML($xml_string, $mensaje_validacion);

                if (! $result) {
                    DB::rollBack();

                    return $this->sendError($mensaje_validacion);
                }

                $xml = $documento->subirXmlDteS3($xml_string);
                $documento->archivos()->attach($xml->id, ['tipo' => TipoArchivo::DTE]);

                ProcesarEnvioDte::dispatch($documento->id);
                //$documento->pdf = base64_encode($pdf_string);
            }

            //DB::rollBack();
            DB::commit();

            return $this->sendResponse($documento->toArray(), 'Documento guardado exitosamente.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return $this->sendError($e->getMessage());
        }
    }

    /**
     * Display the specified Documento.
     * GET|HEAD /documentos/{id}.
     *
     * @param APIRequest $request
     * @param  int $id
     * @param int $empresa_id
     *
     * @return Response
     */
    public function show(APIRequest $request, $id, $empresa_id)
    {
        /** @var Documento $documento */
        $documento = Documento::where('empresa_id', $empresa_id)->find($id);

        if (empty($documento)) {
            return $this->sendError('Documento not found');
        }

        return $this->sendResponse(new DocumentoResource($documento), 'Documento retrieved successfully');
    }

    /**
     * Update the specified Documento in storage.
     * PUT/PATCH /documentos/{id}.
     *
     * @param  int $id
     * @param UpdateDocumentoAPIRequest $request
     *
     * @return Response
     * @throws
     */
    public function update($id, UpdateDocumentoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Documento $documento */
        $documento = $this->documentoRepository->findWithoutFail($id);

        if (empty($documento)) {
            return $this->sendError('Documento not found');
        }

        $documento = $this->documentoRepository->update($input, $id);

        return $this->sendResponse($documento->toArray(), 'Documento updated successfully');
    }

    /**
     * Remove the specified Documento from storage.
     * DELETE /documentos/{id}.
     *
     * @param APIRequest $request
     * @param  int $id
     * @param int $empresa_id
     *
     * @return Response
     */
    public function destroy(APIRequest $request, $id, $empresa_id)
    {
        /** @var Documento $documento */
        $documento = Documento::where('empresa_id', $empresa_id)->find($id);
        $documento = $this->documentoRepository->findWithoutFail($id);

        if (empty($documento)) {
            return $this->sendError('Documento not found');
        }

        $documento->delete();

        return $this->sendResponse($id, 'Documento deleted successfully');
    }

    /**
     * Get the PDF of the specified Documento from storage
     * GET /documentos/{id}/empresa/{empresa_id}/pdf.
     *
     * @param APIRequest $request
     * @param int $id
     * @param int $empresa_id
     * @throws
     */
    public function pdf(APIRequest $request, $id, $empresa_id)
    {
        $this->validate($request, [
            'formato_hoja' => 'sometimes',
            Rule::in(['carta', 'termico'])
        ]);

        /** @var Documento $documento */
        $documento = Documento::where('empresa_id', $empresa_id)->find($id);

        if (empty($documento)) {
            return $this->sendError('Documento no encontrado');
        }

        if(!$request->filled('formato_hoja')){
            $termico = 0;
        }else{
            $termico = $request->input('formato_hoja') == 'termico' ? 1 : 0;
        }

        $pdf = $documento->obtenerPdfString("", $termico);

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

    public function sendPDF(APIRequest $request, $id, $empresa_id)
    {
        /* @var Documento $documento */

        $this->validate($request, ['email_address' => 'required|email']);

        $documento = Documento::where('empresa_id', $empresa_id)->find($id);

        if (empty($documento)) {
            return $this->sendError('Documento no encontrado');
        }


        $email_id = $documento->crearEmailEnvioPDF($request->input('email_address'));
        SendEmailJob::dispatch($email_id, $documento);

        return $this->sendResponse([], '');
    }

    public function xml(APIRequest $request, $id, $empresa_id)
    {
        /** @var Documento $documento */
        $documento = Documento::where('empresa_id', $empresa_id)->find($id);

        if (empty($documento)) {
            return $this->sendError('Documento no encontrado');
        }

        $xml_file = $documento->archivos()->wherePivot('tipo', TipoArchivo::DTE)->latest()->first();
        $xml = Storage::cloud()->get($xml_file->file_path);

        if($request->filled('responseType') && $request->input('responseType') == 'base64'){
            return $this->sendResponse(['file' => base64_encode($xml)], 'XML Obtenido');
        }else{
            return new \Illuminate\Http\Response($xml, 200, array(
                'Content-Type' => 'application/xml',
                'X-Vapor-Base64-Encode' => 'True',
                'Content-Disposition' =>  'attachment; filename="document.xml"',
                'Content-Length' => strlen($xml),
            ));
        }
    }

    public function xmlEnvio(APIRequest $request, $id, $empresa_id)
    {
        /** @var Documento $documento */
        $documento = Documento::where('empresa_id', $empresa_id)->find($id);

        if (empty($documento)) {
            return $this->sendError('Documento no encontrado');
        }

        $envio = $documento->envios()->where('envios_dtes.contribuyente', 1)->latest()->first();

        if(empty($envio)){
            return $this->sendError('El documento no posee un envio contribuyente');
        }

        $xml_file = $envio->archivos()->latest()->first();
        $xml = Storage::cloud()->get($xml_file->file_path);

        if($request->filled('responseType') && $request->input('responseType') == 'base64'){
            return $this->sendResponse(['file' => base64_encode($xml)], 'XML Obtenido');
        }else{
            return new \Illuminate\Http\Response($xml, 200, array(
                'Content-Type' => 'application/xml',
                'X-Vapor-Base64-Encode' => 'True',
                'Content-Disposition' =>  'attachment; filename="envio.xml"',
                'Content-Length' => strlen($xml),
            ));
        }
    }

    public function consultarEstadoSii($empresa_id, Documento $documento)
    {
        if($empresa_id !== $documento->empresa_id){
            return $this->sendError('Documento no encontrado');
        }
        
        return $this->sendResponse(['data' => $documento->consultarEstadoSii(null, true)], '');
    }
}
