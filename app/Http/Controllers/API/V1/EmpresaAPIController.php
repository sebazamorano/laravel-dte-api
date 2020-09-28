<?php

namespace App\Http\Controllers\API\V1;

use App\File;
use Response;
use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\EmpresaResource;
use App\Repositories\EmpresaRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Requests\API\CreateEmpresaAPIRequest;
use App\Http\Requests\API\UpdateEmpresaAPIRequest;

/**
 * Class EmpresaController.
 */
class EmpresaAPIController extends AppBaseController
{
    /** @var EmpresaRepository */
    private $empresaRepository;

    public function __construct(EmpresaRepository $empresaRepo)
    {
        $this->empresaRepository = $empresaRepo;
    }

    /**
     * Display a listing of the Empresa.
     * GET|HEAD /empresas.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $empresas = Auth::user()->misEmpresas();

        return $this->sendResponse($empresas->toArray(), 'Empresas retrieved successfully');
    }

    /**
     * Store a newly created Empresa in storage.
     * POST /empresas.
     *
     * @param CreateEmpresaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEmpresaAPIRequest $request)
    {
        /* @var Empresa $empresas */
        $input = $request->all();

        $empresas = $this->empresaRepository->create($input);

        $sucursal = new Sucursal();
        $sucursal = $sucursal->crearDesdeDatosEmpresa($empresas);

        return $this->sendResponse($empresas->toArray(), 'Empresa saved successfully');
    }

    /**
     * Display the specified Empresa.
     * GET|HEAD /empresas/{empresa_id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Empresa $empresa */
        $empresa = $this->empresaRepository->findWithoutFail($id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        return $this->sendResponse(new EmpresaResource($empresa), 'Empresa retrieved successfully');
    }

    /**
     * Update the specified Empresa in storage.
     * PUT/PATCH /empresas/{id}.
     *
     * @param  int $id
     * @param UpdateEmpresaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmpresaAPIRequest $request)
    {
        $input = $request->all();

        /** @var Empresa $empresa */
        $empresa = $this->empresaRepository->findWithoutFail($id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $empresa = $this->empresaRepository->update($input, $id);
        return $this->sendResponse($empresa->toArray(), 'Empresa updated successfully');
    }

    /**
     * Remove the specified Empresa from storage.
     * DELETE /empresas/{id}.
     *
     * @param int $id
     *
     * @return Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        /** @var Empresa $empresa */
        $empresa = $this->empresaRepository->findWithoutFail($id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $empresa->delete();

        return $this->sendResponse($id, 'Empresa deleted successfully');
    }

    public function subirLogo(Request $request, $empresa_id)
    {
        /* @var $empresa Empresa  */
        $logo_array = File::generateArray($request, 'logo');
        $empresa = Empresa::find($empresa_id);
        $logo = File::store($logo_array, $empresa, 'imagenes');

        if($empresa->logo_id !== null){
            $empresa->logo->deleteSource();
        }

        $empresa->logo_id = $logo->id;
        $empresa->update();

        return $this->sendResponse($logo, 'Logo updated successfully');
    }
}
