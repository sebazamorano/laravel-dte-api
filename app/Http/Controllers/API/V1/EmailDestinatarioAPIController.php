<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Models\EmailDestinatario;
use App\Http\Controllers\AppBaseController;
use App\Repositories\Criteria\RequestCriteria;
use App\Repositories\EmailDestinatarioRepository;
use App\Repositories\Criteria\LimitOffsetCriteria;
use App\Http\Requests\API\CreateEmailDestinatarioAPIRequest;
use App\Http\Requests\API\UpdateEmailDestinatarioAPIRequest;

/**
 * Class EmailDestinatarioController.
 */
class EmailDestinatarioAPIController extends AppBaseController
{
    /** @var EmailDestinatarioRepository */
    private $emailDestinatarioRepository;

    public function __construct(EmailDestinatarioRepository $emailDestinatarioRepo)
    {
        $this->emailDestinatarioRepository = $emailDestinatarioRepo;
    }

    /**
     * Display a listing of the EmailDestinatario.
     * GET|HEAD /emailDestinatarios.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->emailDestinatarioRepository->pushCriteria(new RequestCriteria($request));
        $this->emailDestinatarioRepository->pushCriteria(new LimitOffsetCriteria($request));
        $emailDestinatarios = $this->emailDestinatarioRepository->all();

        return $this->sendResponse($emailDestinatarios->toArray(), 'Email Destinatarios retrieved successfully');
    }

    /**
     * Store a newly created EmailDestinatario in storage.
     * POST /emailDestinatarios.
     *
     * @param CreateEmailDestinatarioAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateEmailDestinatarioAPIRequest $request)
    {
        $input = $request->all();

        $emailDestinatarios = $this->emailDestinatarioRepository->create($input);

        return $this->sendResponse($emailDestinatarios->toArray(), 'Email Destinatario saved successfully');
    }

    /**
     * Display the specified EmailDestinatario.
     * GET|HEAD /emailDestinatarios/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        /** @var EmailDestinatario $emailDestinatario */
        $emailDestinatario = $this->emailDestinatarioRepository->findWithoutFail($id);

        if (empty($emailDestinatario)) {
            return $this->sendError('Email Destinatario not found');
        }

        return $this->sendResponse($emailDestinatario->toArray(), 'Email Destinatario retrieved successfully');
    }

    /**
     * Update the specified EmailDestinatario in storage.
     * PUT/PATCH /emailDestinatarios/{id}.
     *
     * @param  int $id
     * @param UpdateEmailDestinatarioAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEmailDestinatarioAPIRequest $request)
    {
        $input = $request->all();

        /** @var EmailDestinatario $emailDestinatario */
        $emailDestinatario = $this->emailDestinatarioRepository->findWithoutFail($id);

        if (empty($emailDestinatario)) {
            return $this->sendError('Email Destinatario not found');
        }

        $emailDestinatario = $this->emailDestinatarioRepository->update($input, $id);

        return $this->sendResponse($emailDestinatario->toArray(), 'EmailDestinatario updated successfully');
    }

    /**
     * Remove the specified EmailDestinatario from storage.
     * DELETE /emailDestinatarios/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var EmailDestinatario $emailDestinatario */
        $emailDestinatario = $this->emailDestinatarioRepository->findWithoutFail($id);

        if (empty($emailDestinatario)) {
            return $this->sendError('Email Destinatario not found');
        }

        $emailDestinatario->delete();

        return $this->sendResponse($id, 'Email Destinatario deleted successfully');
    }
}
