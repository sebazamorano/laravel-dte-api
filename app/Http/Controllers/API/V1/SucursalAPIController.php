<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\Models\Empresa;
use App\Models\Sucursal;
use Illuminate\Http\Request;
use App\Http\Request\APIRequest;
use App\Repositories\SucursalRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Request\API\CreateSucursalAPIRequest;
use App\Http\Request\API\UpdateSucursalAPIRequest;

/**
 * Class SucursalController.
 */
class SucursalAPIController extends AppBaseController
{
    /** @var SucursalRepository */
    private $sucursalRepository;

    public function __construct(SucursalRepository $sucursalRepo)
    {
        $this->sucursalRepository = $sucursalRepo;
    }

    /**
     * Display a listing of the branch offices of the company.
     * GET|HEAD /empresas/{empresa_id}/branch_offices.
     *
     * @param APIRequest $request
     * @param int $empresa_id
     * @return Response
     */
    public function index(APIRequest $request, $empresa_id)
    {
        $empresa = Empresa::find($empresa_id);

        if ($request->filled('paginate')) {
            $branch_offices = $empresa->sucursales()->paginate($request->input('paginate'));
        } else {
            $branch_offices = $empresa->sucursales()->paginate(10);
        }

        return $this->sendResponse($branch_offices->toArray(), 'Branch offices retrieved successfully');
    }

    /**
     * Store a newly created Sucursal in storage.
     * POST /empresas/{empresa_id}/branch_offices.
     *
     * @param CreateSucursalAPIRequest $request
     * @return Response
     * @throws
     */
    public function Store(CreateSucursalAPIRequest $request)
    {
        $input = $request->all();
        $branch_office = $this->sucursalRepository->create($input);

        return $this->sendResponse($branch_office->toArray(), 'Branch office saved successfully');
    }

    /**
     * Display the specified Sucursal.
     * GET|HEAD /empresas/{empresa_id}/branch_offices/{id}.
     * @param APIRequest $request
     * @param  int $id
     *
     * @return Response
     */
    public function show(APIRequest $request, $id)
    {
        /** @var Sucursal $sucursal */
        $sucursal = $this->sucursalRepository->findWithoutFail($id);

        if (empty($sucursal)) {
            return $this->sendError('Sucursal not found');
        }

        return $this->sendResponse($sucursal->toArray(), 'Sucursal retrieved successfully');
    }

    /**
     * Update the specified Sucursal in storage.
     * PUT/PATCH /empresas/{empresa_id}/branch_offices/{id}.
     *
     * @param  int $id
     * @param UpdateSucursalAPIRequest $request
     *
     * @return Response
     * @throws
     */
    public function update($id, UpdateSucursalAPIRequest $request)
    {
        $input = $request->all();

        /** @var Sucursal $sucursal */
        $sucursal = $this->sucursalRepository->findWithoutFail($id);

        if (empty($sucursal)) {
            return $this->sendError('Sucursal not found');
        }

        $sucursal = $this->sucursalRepository->update($input, $id);

        return $this->sendResponse($sucursal->toArray(), 'Sucursal updated successfully');
    }

    /**
     * Remove the specified Sucursal from storage.
     * DELETE /empresas/{empresa_id}/branch_offices/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Sucursal $sucursal */
        $sucursal = $this->sucursalRepository->findWithoutFail($id);

        if (empty($sucursal)) {
            return $this->sendError('Sucursal not found');
        }

        $sucursal->delete();

        return $this->sendResponse($id, 'Sucursal deleted successfully');
    }
}
