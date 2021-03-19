<?php

namespace App\Http\Controllers;

use App\Components\CAF as CAFComponent;
use App\File;
use App\Http\Requests\SubirCafRequest;
use App\Models\Caf;
use App\Models\Empresa as Company;
use App\Repositories\CafRepository;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CafController extends Controller
{

    /** @var CafRepository */
    private $cafRepository;

    public function __construct(CafRepository $cafRepo)
    {
        $this->cafRepository = $cafRepo;
    }

    public function setearEnUso(Company $company, $caf)
    {
        $caf_company = $company->cafs()->where('cafs.id', $caf)->first();

        $company->cafs()
            ->where('cafs.id', '!=', $caf)
            ->where('cafs.tipo_documento_id', $caf_company->tipo_documento_id)
            ->update(['enUso' => 0]);
        $caf_company->enUso = true;
        $caf_company->save();
        request()->session()->flash('success-message', ['CAF actualizado']);

        return redirect(route('companies.show', ['company' => $company->id]));
    }

    public function subir(Company $company)
    {
        return view('empresas.cafs_subir')->with(compact('company'));
    }

    public function upload(Company $company, SubirCafRequest $request)
    {
        /* @var Company $empresa */
        $input = $request->validated();
        $input['empresa_id'] = $company->id;

        if(isset($input['enUso'])){
            $input['enUso'] = true;
        }else{
            $input['enUso'] = false;
        }

        $caf_data = CAFComponent::leerCAF($request->file('caf')->path());
        $caf_data['folioActual'] = $request->input('folioActual');

        $empresa = Company::where('rut', $caf_data['rut'])->first();
        Caf::verificarEmpresa($company->id, $empresa);

        $autorizado = $empresa->documentoEstaAutorizado($caf_data['td']);

        if (! $autorizado) {
            request()->session()->flash('error-message', ['El tipo documento no esta autorizado, no puede subir CAF']);
            return redirect()->back();
        }

        Caf::validarUnique($caf_data);

        $fileUpload = File::uploadFileFromRequest($request, 'caf', 'caf', $company);
        $input['file_id'] = $fileUpload->id;

        $input = Caf::prepararInformacionParaCrear($input, $caf_data);

        try {
            $caf = $this->cafRepository->create($input);

            if ($input['enUso'] == 1) {
                Caf::where('id', '!=', $caf->id)->where('empresa_id', $input['empresa_id'])->where('tipo_documento_id', $caf->tipo_documento_id)->update(['enUso' => 0]);
            }
        } catch (\Throwable $e) {
            Log::error('Error al subir folio '.$e->getMessage());

            request()->session()->flash('error-message', ['Existio un error al procesar la información']);
            return redirect()->back();
        }

        request()->session()->flash('success-message', ['Caf guardado exitosamente']);
        return redirect(route('companies.show', ['company' => $company->id]));
    }
}
