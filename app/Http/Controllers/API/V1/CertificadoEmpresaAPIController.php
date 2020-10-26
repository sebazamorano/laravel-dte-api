<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\File as File;
use App\Components\Sii;
use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Models\CertificadoEmpresa;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\CertificadoEmpresaRepository;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateCertificadoEmpresaAPIRequest;
use App\Http\Request\API\UpdateCertificadoEmpresaAPIRequest;

/**
 * Class CertificadoEmpresaController.
 */
class CertificadoEmpresaAPIController extends AppBaseController
{
    /** @var CertificadoEmpresaRepository */
    private $certificadoEmpresaRepository;

    /** @var Sii */
    private $siiComponent;

    public function __construct(CertificadoEmpresaRepository $certificadoEmpresaRepo, Sii $siiComponent)
    {
        $this->certificadoEmpresaRepository = $certificadoEmpresaRepo;
        $this->siiComponent = $siiComponent;
    }

    /**
     * Display a listing of the CertificadoEmpresa.
     * GET|HEAD /certificadosEmpresas.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request, $empresa_id)
    {
        $empresa = Empresa::find($empresa_id);

        $this->certificadoEmpresaRepository->pushCriteria(new RequestCriteria($request));
        $this->certificadoEmpresaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $certificadosEmpresas = $this->certificadoEmpresaRepository->all();

        return $this->sendResponse($certificadosEmpresas->toArray(), 'Certificados Empresas retrieved successfully');
    }

    /**
     * Store a newly created CertificadoEmpresa in storage.
     * POST /certificadosEmpresas.
     *
     * @param CreateCertificadoEmpresaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateCertificadoEmpresaAPIRequest $request)
    {
        $p12 = Empresa::parseCertificado($request);

        $p12data = openssl_x509_parse($p12['cert']);
        $validFrom = strtotime(date('Y-m-d H:i:s', $p12data['validFrom_time_t']));
        $validTo = strtotime(date('Y-m-d H:i:s', $p12data['validTo_time_t']));
        $nombrePem = str_replace(['.pfx', '.p12'], '.pem', $request->original->getClientOriginalName());

        $nombre_archivo_temporal = uniqid().'.pem';
        Storage::put($nombre_archivo_temporal, $p12['cert'].$p12['pkey']);
        $cookieJar = $this->siiComponent->obtenerCookies(Storage::path($nombre_archivo_temporal), $request->input('password'));
        Storage::delete($nombre_archivo_temporal);
        $rut = $cookieJar->getCookieByName('RUT_NS')->getValue().'-'.$cookieJar->getCookieByName('DV_NS')->getValue();

        $file = new File;
        $fileUpload = $file->uploadFileFromRequest($request, 'original', 'certificados');

        $filePem = new File;
        $empresa = Empresa::find($request->input('empresa_id'));
        $fileUploadPem = $filePem->uploadFileFromContent($empresa, $p12['cert'].$p12['pkey'], $nombrePem, 'application/x-pem-file', 0, 'certificados');

        $input = $request->all();
        $input['rut'] = $rut;
        $input['original'] = $fileUpload->id;
        $input['pem'] = $fileUploadPem->id;
        $input['fechaEmision'] = date(('Y-m-d H:i:s'), $validFrom);
        $input['fechaVencimiento'] = date(('Y-m-d H:i:s'), $validTo);
        $input['subject'] = json_encode($p12data['subject']);
        $input['issuer'] = json_encode($p12data['issuer']);

        $certificadoEmpresa = $this->certificadoEmpresaRepository->create($input);

        /* @var $empresa Empresa */
        $empresa = Empresa::find($request->empresa_id);
        $empresa->certificados()->where('id', '!=', $certificadoEmpresa->id)->update(['enUso' => 0]);

        return $this->sendResponse($certificadoEmpresa->toArray(), 'Certificado Empresa saved successfully');
    }

    /**
     * Display the specified CertificadoEmpresa.
     * GET|HEAD /certificadosEmpresas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var CertificadoEmpresa $certificadoEmpresa */
        $certificadoEmpresa = $this->certificadoEmpresaRepository->findWithoutFail($id);

        if (empty($certificadoEmpresa)) {
            return $this->sendError('Certificado Empresa not found');
        }

        return $this->sendResponse($certificadoEmpresa->toArray(), 'Certificado Empresa retrieved successfully');
    }

    /**
     * Update the specified CertificadoEmpresa in storage.
     * PUT/PATCH /certificadosEmpresas/{id}.
     *
     * @param  int $id
     * @param UpdateCertificadoEmpresaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCertificadoEmpresaAPIRequest $request)
    {
        $input = $request->all();

        /** @var CertificadoEmpresa $certificadoEmpresa */
        $certificadoEmpresa = $this->certificadoEmpresaRepository->findWithoutFail($id);

        if (empty($certificadoEmpresa)) {
            return $this->sendError('Certificado Empresa not found');
        }

        $certificadoEmpresa = $this->certificadoEmpresaRepository->update($input, $id);

        return $this->sendResponse($certificadoEmpresa->toArray(), 'CertificadoEmpresa updated successfully');
    }

    /**
     * Remove the specified CertificadoEmpresa from storage.
     * DELETE /certificadosEmpresas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var CertificadoEmpresa $certificadoEmpresa */
        $certificadoEmpresa = $this->certificadoEmpresaRepository->findWithoutFail($id);

        if (empty($certificadoEmpresa)) {
            return $this->sendError('Certificado Empresa not found');
        }

        $certificadoEmpresa->delete();

        return $this->sendResponse($id, 'Certificado Empresa deleted successfully');
    }
}
