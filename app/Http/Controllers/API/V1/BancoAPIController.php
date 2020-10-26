<?php

namespace App\Http\Controllers\API\V1;

use Response;
use App\Models\Banco;
use Illuminate\Http\Request;
use App\Repositories\BancoRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Request\API\CreateBancoAPIRequest;
use App\Http\Request\API\UpdateBancoAPIRequest;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\Criteria\LimitOffsetCriteria;

/**
 * Class BancoController.
 */
class BancoAPIController extends AppBaseController
{
    /** @var BancoRepository */
    private $bancoRepository;

    public function __construct(BancoRepository $bancoRepo)
    {
        $this->bancoRepository = $bancoRepo;
    }

    /**
     * Display a listing of the Banco.
     * GET|HEAD /bancos.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->bancoRepository->pushCriteria(new RequestCriteria($request));
        $this->bancoRepository->pushCriteria(new LimitOffsetCriteria($request));
        $bancos = $this->bancoRepository->all();

        return $this->sendResponse($bancos->toArray(), 'Bancos retrieved successfully');
    }

    /**
     * Store a newly created Banco in storage.
     * POST /bancos.
     *
     * @param CreateBancoAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateBancoAPIRequest $request)
    {
        $input = $request->all();

        $bancos = $this->bancoRepository->create($input);

        return $this->sendResponse($bancos->toArray(), 'Banco saved successfully');
    }

    /**
     * Display the specified Banco.
     * GET|HEAD /bancos/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var Banco $banco */
        $banco = $this->bancoRepository->findWithoutFail($id);

        if (empty($banco)) {
            return $this->sendError('Banco not found');
        }

        return $this->sendResponse($banco->toArray(), 'Banco retrieved successfully');
    }

    /**
     * Update the specified Banco in storage.
     * PUT/PATCH /bancos/{id}.
     *
     * @param  int $id
     * @param UpdateBancoAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateBancoAPIRequest $request)
    {
        $input = $request->all();

        /** @var Banco $banco */
        $banco = $this->bancoRepository->findWithoutFail($id);

        if (empty($banco)) {
            return $this->sendError('Banco not found');
        }

        $banco = $this->bancoRepository->update($input, $id);

        return $this->sendResponse($banco->toArray(), 'Banco updated successfully');
    }

    /**
     * Remove the specified Banco from storage.
     * DELETE /bancos/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var Banco $banco */
        $banco = $this->bancoRepository->findWithoutFail($id);

        if (empty($banco)) {
            return $this->sendError('Banco not found');
        }

        $banco->delete();

        return $this->sendResponse($id, 'Banco deleted successfully');
    }
}
