<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\TipoDocumento;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TipoDocumentoRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateTipoDocumentoAPIRequest;
use App\Http\Requests\API\UpdateTipoDocumentoAPIRequest;

/**
 * Class TipoDocumentoController.
 */
class TipoDocumentoAPIController extends AppBaseController
{
    /** @var TipoDocumentoRepository */
    private $tipoDocumentoRepository;

    public function __construct(TipoDocumentoRepository $tipoDocumentoRepo)
    {
        $this->tipoDocumentoRepository = $tipoDocumentoRepo;
    }

    /**
     * Display a listing of the TipoDocumento.
     * GET|HEAD /tipoDocumentos.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->tipoDocumentoRepository->pushCriteria(new RequestCriteria($request));
        $this->tipoDocumentoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $tipoDocumentos = $this->tipoDocumentoRepository->all();

        return $this->sendResponse($tipoDocumentos->toArray(), 'Tipo Documentos retrieved successfully');
    }

    /**
     * Store a newly created TipoDocumento in storage.
     * POST /tipoDocumentos.
     *
     * @param CreateTipoDocumentoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoDocumentoAPIRequest $request)
    {
        $input = $request->all();

        $tipoDocumentos = $this->tipoDocumentoRepository->create($input);

        return $this->sendResponse($tipoDocumentos->toArray(), 'Tipo Documento saved successfully');
    }

    /**
     * Display the specified TipoDocumento.
     * GET|HEAD /tipoDocumentos/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var TipoDocumento $tipoDocumento */
        $tipoDocumento = $this->tipoDocumentoRepository->findWithoutFail($id);

        if (empty($tipoDocumento)) {
            return $this->sendError('Tipo Documento not found');
        }

        return $this->sendResponse($tipoDocumento->toArray(), 'Tipo Documento retrieved successfully');
    }

    /**
     * Update the specified TipoDocumento in storage.
     * PUT/PATCH /tipoDocumentos/{id}.
     *
     * @param  int $id
     * @param UpdateTipoDocumentoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoDocumentoAPIRequest $request)
    {
        $input = $request->all();

        /** @var TipoDocumento $tipoDocumento */
        $tipoDocumento = $this->tipoDocumentoRepository->findWithoutFail($id);

        if (empty($tipoDocumento)) {
            return $this->sendError('Tipo Documento not found');
        }

        $tipoDocumento = $this->tipoDocumentoRepository->update($input, $id);

        return $this->sendResponse($tipoDocumento->toArray(), 'TipoDocumento updated successfully');
    }

    /**
     * Remove the specified TipoDocumento from storage.
     * DELETE /tipoDocumentos/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TipoDocumento $tipoDocumento */
        $tipoDocumento = $this->tipoDocumentoRepository->findWithoutFail($id);

        if (empty($tipoDocumento)) {
            return $this->sendError('Tipo Documento not found');
        }

        $tipoDocumento->delete();

        return $this->sendResponse($id, 'Tipo Documento deleted successfully');
    }
}
