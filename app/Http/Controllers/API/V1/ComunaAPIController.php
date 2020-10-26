<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\Models\Comuna;
use Illuminate\Http\Request;
use App\Repositories\ComunaRepository;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Http\Request\API\CreateComunaAPIRequest;
use App\Http\Request\API\UpdateComunaAPIRequest;
use App\Repositories\Criteria\LimitOffsetCriteria;

/**
 * Class ComunaController.
 */
class ComunaAPIController extends AppBaseController
{
    /** @var ComunaRepository */
    private $comunaRepository;

    public function __construct(ComunaRepository $comunaRepo)
    {
        $this->comunaRepository = $comunaRepo;
    }

    /**
     * Display a listing of the Comuna.
     * GET|HEAD /comunas.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->comunaRepository->pushCriteria(new RequestCriteria($request));
        $this->comunaRepository->pushCriteria(new LimitOffsetCriteria($request));
        $comunas = $this->comunaRepository->all();

        return $this->sendResponse($comunas->toArray(), 'Comunas retrieved successfully');
    }

    /**
     * Store a newly created Comuna in storage.
     * POST /comunas.
     *
     * @param CreateComunaAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateComunaAPIRequest $request)
    {
        $input = $request->all();

        $comunas = $this->comunaRepository->create($input);

        return $this->sendResponse($comunas->toArray(), 'Comuna saved successfully');
    }

    /**
     * Display the specified Comuna.
     * GET|HEAD /comunas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Comuna $comuna */
        $comuna = $this->comunaRepository->findWithoutFail($id);

        if (empty($comuna)) {
            return $this->sendError('Comuna not found');
        }

        return $this->sendResponse($comuna->toArray(), 'Comuna retrieved successfully');
    }

    /**
     * Update the specified Comuna in storage.
     * PUT/PATCH /comunas/{id}.
     *
     * @param  int $id
     * @param UpdateComunaAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateComunaAPIRequest $request)
    {
        $input = $request->all();

        /** @var Comuna $comuna */
        $comuna = $this->comunaRepository->findWithoutFail($id);

        if (empty($comuna)) {
            return $this->sendError('Comuna not found');
        }

        $comuna = $this->comunaRepository->update($input, $id);

        return $this->sendResponse($comuna->toArray(), 'Comuna updated successfully');
    }

    /**
     * Remove the specified Comuna from storage.
     * DELETE /comunas/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Comuna $comuna */
        $comuna = $this->comunaRepository->findWithoutFail($id);

        if (empty($comuna)) {
            return $this->sendError('Comuna not found');
        }

        $comuna->delete();

        return $this->sendResponse($id, 'Comuna deleted successfully');
    }
}
