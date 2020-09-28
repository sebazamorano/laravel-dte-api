<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\ActividadEconomica;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\ActividadEconomicaRepository;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateActividadEconomicaAPIRequest;
use App\Http\Requests\API\UpdateActividadEconomicaAPIRequest;

/**
 * Class ActividadEconomicaController.
 */
class ActividadEconomicaAPIController extends AppBaseController
{
    /** @var ActividadEconomicaRepository */
    private $actividadEconomicaRepository;

    public function __construct(ActividadEconomicaRepository $actividadEconomicaRepo)
    {
        $this->actividadEconomicaRepository = $actividadEconomicaRepo;
    }

    /**
     * Display a listing of the ActividadEconomica.
     * GET|HEAD /actividadesEconomicas.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->actividadEconomicaRepository->pushCriteria(new RequestCriteria($request));
        $this->actividadEconomicaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $actividadesEconomicas = $this->actividadEconomicaRepository->all();

        return $this->sendResponse($actividadesEconomicas->toArray(), 'Actividades Economicas retrieved successfully');
    }

    /**
     * Store a newly created ActividadEconomica in storage.
     * POST /actividadesEconomicas.
     *
     * @param CreateActividadEconomicaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateActividadEconomicaAPIRequest $request)
    {
        $input = $request->all();

        $actividadesEconomicas = $this->actividadEconomicaRepository->create($input);

        return $this->sendResponse($actividadesEconomicas->toArray(), 'Actividad Economica saved successfully');
    }

    /**
     * Display the specified ActividadEconomica.
     * GET|HEAD /actividadesEconomicas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var ActividadEconomica $actividadEconomica */
        $actividadEconomica = $this->actividadEconomicaRepository->findWithoutFail($id);

        if (empty($actividadEconomica)) {
            return $this->sendError('Actividad Economica not found');
        }

        return $this->sendResponse($actividadEconomica->toArray(), 'Actividad Economica retrieved successfully');
    }

    /**
     * Update the specified ActividadEconomica in storage.
     * PUT/PATCH /actividadesEconomicas/{id}.
     *
     * @param  int $id
     * @param UpdateActividadEconomicaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateActividadEconomicaAPIRequest $request)
    {
        $input = $request->all();

        /** @var ActividadEconomica $actividadEconomica */
        $actividadEconomica = $this->actividadEconomicaRepository->findWithoutFail($id);

        if (empty($actividadEconomica)) {
            return $this->sendError('Actividad Economica not found');
        }

        $actividadEconomica = $this->actividadEconomicaRepository->update($input, $id);

        return $this->sendResponse($actividadEconomica->toArray(), 'ActividadEconomica updated successfully');
    }

    /**
     * Remove the specified ActividadEconomica from storage.
     * DELETE /actividadesEconomicas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var ActividadEconomica $actividadEconomica */
        $actividadEconomica = $this->actividadEconomicaRepository->findWithoutFail($id);

        if (empty($actividadEconomica)) {
            return $this->sendError('Actividad Economica not found');
        }

        $actividadEconomica->delete();

        return $this->sendResponse($id, 'Actividad Economica deleted successfully');
    }
}
