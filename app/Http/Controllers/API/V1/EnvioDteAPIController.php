<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\Models\EnvioDte;
use Illuminate\Http\Request;
use App\Repositories\EnvioDteRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Request\API\CreateEnvioDteAPIRequest;
use App\Http\Request\API\UpdateEnvioDteAPIRequest;

/**
 * Class EnvioDteController.
 */
class EnvioDteAPIController extends AppBaseController
{
    /** @var EnvioDteRepository */
    private $envioDteRepository;

    public function __construct(EnvioDteRepository $envioDteRepo)
    {
        $this->envioDteRepository = $envioDteRepo;
    }

    /**
     * Display a listing of the EnvioDte.
     * GET|HEAD /enviosDtes.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->envioDteRepository->pushCriteria(new RequestCriteria($request));
        $this->envioDteRepository->pushCriteria(new LimitOffsetCriteria($request));
        $enviosDtes = $this->envioDteRepository->all();

        return $this->sendResponse($enviosDtes->toArray(), 'Envios Dtes retrieved successfully');
    }

    /**
     * Store a newly created EnvioDte in storage.
     * POST /enviosDtes.
     *
     * @param CreateEnvioDteAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEnvioDteAPIRequest $request)
    {
        $input = $request->all();

        $enviosDtes = $this->envioDteRepository->create($input);

        return $this->sendResponse($enviosDtes->toArray(), 'Envio Dte saved successfully');
    }

    /**
     * Display the specified EnvioDte.
     * GET|HEAD /enviosDtes/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EnvioDte $envioDte */
        $envioDte = $this->envioDteRepository->findWithoutFail($id);

        if (empty($envioDte)) {
            return $this->sendError('Envio Dte not found');
        }

        return $this->sendResponse($envioDte->toArray(), 'Envio Dte retrieved successfully');
    }

    /**
     * Update the specified EnvioDte in storage.
     * PUT/PATCH /enviosDtes/{id}.
     *
     * @param  int $id
     * @param UpdateEnvioDteAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEnvioDteAPIRequest $request)
    {
        $input = $request->all();

        /** @var EnvioDte $envioDte */
        $envioDte = $this->envioDteRepository->findWithoutFail($id);

        if (empty($envioDte)) {
            return $this->sendError('Envio Dte not found');
        }

        $envioDte = $this->envioDteRepository->update($input, $id);

        return $this->sendResponse($envioDte->toArray(), 'EnvioDte updated successfully');
    }

    /**
     * Remove the specified EnvioDte from storage.
     * DELETE /enviosDtes/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EnvioDte $envioDte */
        $envioDte = $this->envioDteRepository->findWithoutFail($id);

        if (empty($envioDte)) {
            return $this->sendError('Envio Dte not found');
        }

        $envioDte->delete();

        return $this->sendResponse($id, 'Envio Dte deleted successfully');
    }
}
