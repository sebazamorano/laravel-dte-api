<?php

namespace App\Http\Controllers;

use App\Components\Sii;
use App\Http\Requests\StoreSyncSiiRequest;
use App\Http\Requests\UpdateCompanyControllerRequest;
use App\Models\CertificadoEmpresa;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Empresa::paginate();
        return view('empresas.index')->with(compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('empresas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function store(StoreSyncSiiRequest $request)
    {
        DB::beginTransaction();
        try {
            $input = $request->validated();
            $sii = new Sii();

            $p12 = CertificadoEmpresa::parsearCertificado($request);
            $cookieJar = CertificadoEmpresa::obtenerCookieJarDesdeCertificadoTemporal($request, $p12);
            $csv_tmp = Storage::put(uniqid() . '.csv', '');
            $response = $sii->downloadCompanyData($input['identity_card'], Storage::path($csv_tmp), $cookieJar);

            if($response === false){
                request()->session()->flash('error-message', ['Error al intentar crear empresa, intente más tarde!']);
                return redirect(route('companies.index'));
            }

            $company_data = Sii::transformCsvCompanyDataToArray(Storage::path($csv_tmp));
            Storage::delete($csv_tmp);

            $empresa = Empresa::crearEmpresaDesdeSiiData($company_data);
            $certificado = CertificadoEmpresa::crearCertificado($request, $empresa, $cookieJar);

            if($certificado === false){
                DB::rollBack();
                request()->session()->flash('error-message', ['Error al intentar crear empresa, intente más tarde!']);
                return redirect(route('companies.index'));
            }

            $documentosAutorizados = $request->input('documentoAutorizado');
            $empresa->documentosAutorizados()->sync($documentosAutorizados);

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            request()->session()->flash('error-message', ['Error al intentar crear empresa, intente más tarde!', $e->getMessage() . $e->getCode() . $e->getFile() . $e->getLine()]);
            return redirect(route('companies.index'));
        }

        request()->session()->flash('success-message', ['Empresa creada con exito!']);
        return redirect(route('companies.show', ['company' => $empresa->id]));
    }

    /**
     * Display the specified resource.
     *
     * @param Empresa $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show(Empresa $company)
    {
        $cafs = $company->cafs()->orderBy('cafs.id', 'desc')->paginate(7);
        return view('empresas.show')->with(['company' => $company, 'companyBranchOffices' => $company->companyBranchOffices, 'cafs' => $cafs]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Empresa $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Empresa $company)
    {
        return view('empresas.edit')->with(compact('company'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param $company
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector
     */
    public function update(UpdateCompanyControllerRequest $request, Empresa $company)
    {
        $input = $request->validated();
        $company->update($input);
        request()->session()->flash('success-message', ['Empresa actualizada con exito!']);
        return redirect(route('companies.show', ['company' => $company->id]));
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
}
