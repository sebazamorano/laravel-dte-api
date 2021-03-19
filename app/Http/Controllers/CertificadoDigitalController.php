<?php

namespace App\Http\Controllers;

use App\Components\Sii;
use App\File as File;
use App\Http\Requests\UploadDigitalCertificateRequest;
use App\Models\Empresa;
use App\Models\Empresa as Company;
use App\Repositories\CertificadoEmpresaRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificadoDigitalController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function subir(Company $company)
    {
        return view('empresas.certificados_subir')->with(compact('company'));
    }

    public function upload(Company $company, UploadDigitalCertificateRequest $request)
    {
        $p12 = Empresa::parseCertificado($request);

        if($p12 === false){
            return redirect()->back();
        }

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
        $fileUpload = $file->uploadFileFromRequest($request, 'original', 'certificados', $company);

        $filePem = new File;
        $fileUploadPem = $filePem->uploadFileFromContent($company, $p12['cert'].$p12['pkey'], $nombrePem, 'application/x-pem-file', 0, 'certificados');

        $input = $request->all();
        $input['empresa_id'] = $company->id;
        $input['rut'] = $rut;
        $input['original'] = $fileUpload->id;
        $input['pem'] = $fileUploadPem->id;
        $input['fechaEmision'] = date(('Y-m-d H:i:s'), $validFrom);
        $input['fechaVencimiento'] = date(('Y-m-d H:i:s'), $validTo);
        $input['subject'] = json_encode($p12data['subject']);
        $input['issuer'] = json_encode($p12data['issuer']);

        $certificadoEmpresa = $this->certificadoEmpresaRepository->create($input);
        $company->certificados()->where('id', '!=', $certificadoEmpresa->id)->update(['enUso' => 0]);
        request()->session()->flash('success-message', ['Certificado Digital guardado exitosamente']);

        return redirect(route('companies.show', ['company' => $company->id]));
    }

    public function setearEnUso(Company $company, $certificadoDigital)
    {
        $certificado = $company->certificados()->where('certificados_empresas.id', $certificadoDigital)->first();
        $company->certificados()->where('certificados_empresas.id', '!=', $certificadoDigital)->update(['enUso' => 0]);
        $certificado->enUso = true;
        $certificado->save();
        request()->session()->flash('success-message', ['Certificado Digital actualizado']);

        return redirect(route('companies.show', ['company' => $company->id]));
    }
}
