<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\Contribuyente;
use App\Http\Controllers\AppBaseController;
use App\Repositories\ContribuyenteRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateContribuyenteAPIRequest;
use App\Http\Requests\API\UpdateContribuyenteAPIRequest;

/**
 * Class ContribuyenteController.
 */
class ContribuyenteAPIController extends AppBaseController
{
    /** @var ContribuyenteRepository */
    private $contribuyenteRepository;

    public function __construct(ContribuyenteRepository $contribuyenteRepo)
    {
        $this->contribuyenteRepository = $contribuyenteRepo;
    }

    /**
     * Display a listing of the Contribuyente.
     * GET|HEAD /contribuyentes.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->contribuyenteRepository->pushCriteria(new RequestCriteria($request));
        $this->contribuyenteRepository->pushCriteria(new LimitOffsetCriteria($request));
        $contribuyentes = $this->contribuyenteRepository->all();

        return $this->sendResponse($contribuyentes->toArray(), 'Contribuyentes retrieved successfully');
    }

    /**
     * Store a newly created Contribuyente in storage.
     * POST /contribuyentes.
     *
     * @param CreateContribuyenteAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateContribuyenteAPIRequest $request)
    {
        $input = $request->all();

        $contribuyentes = $this->contribuyenteRepository->create($input);

        return $this->sendResponse($contribuyentes->toArray(), 'Contribuyente saved successfully');
    }

    /**
     * Display the specified Contribuyente.
     * GET|HEAD /contribuyentes/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Contribuyente $contribuyente */
        $contribuyente = $this->contribuyenteRepository->findWithoutFail($id);

        if (empty($contribuyente)) {
            return $this->sendError('Contribuyente not found');
        }

        return $this->sendResponse($contribuyente->toArray(), 'Contribuyente retrieved successfully');
    }

    /**
     * Update the specified Contribuyente in storage.
     * PUT/PATCH /contribuyentes/{id}.
     *
     * @param  int $id
     * @param UpdateContribuyenteAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateContribuyenteAPIRequest $request)
    {
        $input = $request->all();

        /** @var Contribuyente $contribuyente */
        $contribuyente = $this->contribuyenteRepository->findWithoutFail($id);

        if (empty($contribuyente)) {
            return $this->sendError('Contribuyente not found');
        }

        $contribuyente = $this->contribuyenteRepository->update($input, $id);

        return $this->sendResponse($contribuyente->toArray(), 'Contribuyente updated successfully');
    }

    /**
     * Remove the specified Contribuyente from storage.
     * DELETE /contribuyentes/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Contribuyente $contribuyente */
        $contribuyente = $this->contribuyenteRepository->findWithoutFail($id);

        if (empty($contribuyente)) {
            return $this->sendError('Contribuyente not found');
        }

        $contribuyente->delete();

        return $this->sendResponse($id, 'Contribuyente deleted successfully');
    }
}
