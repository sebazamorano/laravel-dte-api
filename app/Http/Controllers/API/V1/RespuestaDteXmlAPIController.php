<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\RespuestaDteXml;
use App\Http\Controllers\AppBaseController;
use App\Repositories\RespuestaDteXmlRepository;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateRespuestaDteXmlAPIRequest;
use App\Http\Requests\API\UpdateRespuestaDteXmlAPIRequest;

/**
 * Class RespuestaDteXmlController.
 */
class RespuestaDteXmlAPIController extends AppBaseController
{
    /** @var RespuestaDteXmlRepository */
    private $respuestaDteXmlRepository;

    public function __construct(RespuestaDteXmlRepository $respuestaDteXmlRepo)
    {
        $this->respuestaDteXmlRepository = $respuestaDteXmlRepo;
    }

    /**
     * Display a listing of the RespuestaDteXml.
     * GET|HEAD /respuestaDteXmls.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->respuestaDteXmlRepository->pushCriteria(new RequestCriteria($request));
        $this->respuestaDteXmlRepository->pushCriteria(new LimitOffsetCriteria($request));
        $respuestaDteXmls = $this->respuestaDteXmlRepository->all();

        return $this->sendResponse($respuestaDteXmls->toArray(), 'Respuesta Dte Xmls retrieved successfully');
    }

    /**
     * Store a newly created RespuestaDteXml in storage.
     * POST /respuestaDteXmls.
     *
     * @param CreateRespuestaDteXmlAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateRespuestaDteXmlAPIRequest $request)
    {
        $input = $request->all();

        $respuestaDteXmls = $this->respuestaDteXmlRepository->create($input);

        return $this->sendResponse($respuestaDteXmls->toArray(), 'Respuesta Dte Xml saved successfully');
    }

    /**
     * Display the specified RespuestaDteXml.
     * GET|HEAD /respuestaDteXmls/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var RespuestaDteXml $respuestaDteXml */
        $respuestaDteXml = $this->respuestaDteXmlRepository->findWithoutFail($id);

        if (empty($respuestaDteXml)) {
            return $this->sendError('Respuesta Dte Xml not found');
        }

        return $this->sendResponse($respuestaDteXml->toArray(), 'Respuesta Dte Xml retrieved successfully');
    }

    /**
     * Update the specified RespuestaDteXml in storage.
     * PUT/PATCH /respuestaDteXmls/{id}.
     *
     * @param  int $id
     * @param UpdateRespuestaDteXmlAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRespuestaDteXmlAPIRequest $request)
    {
        $input = $request->all();

        /** @var RespuestaDteXml $respuestaDteXml */
        $respuestaDteXml = $this->respuestaDteXmlRepository->findWithoutFail($id);

        if (empty($respuestaDteXml)) {
            return $this->sendError('Respuesta Dte Xml not found');
        }

        $respuestaDteXml = $this->respuestaDteXmlRepository->update($input, $id);

        return $this->sendResponse($respuestaDteXml->toArray(), 'RespuestaDteXml updated successfully');
    }

    /**
     * Remove the specified RespuestaDteXml from storage.
     * DELETE /respuestaDteXmls/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var RespuestaDteXml $respuestaDteXml */
        $respuestaDteXml = $this->respuestaDteXmlRepository->findWithoutFail($id);

        if (empty($respuestaDteXml)) {
            return $this->sendError('Respuesta Dte Xml not found');
        }

        $respuestaDteXml->delete();

        return $this->sendResponse($id, 'Respuesta Dte Xml deleted successfully');
    }
}
