<?php

namespace App\Http\Controllers\API\V1;

use Response;
use Illuminate\Http\Request;
use App\Http\Request\APIRequest;
use App\Models\TicketCreditNote;
use App\Http\Controllers\AppBaseController;
use App\Repositories\TicketCreditNoteRepository;
use App\Http\Resources\TicketCreditNoteCollection;
use App\Http\Request\API\CreateTicketCreditNoteAPIRequest;
use App\Http\Request\API\UpdateTicketCreditNoteAPIRequest;

/**
 * Class TicketCreditNoteController.
 */
class TicketCreditNoteAPIController extends AppBaseController
{
    /** @var TicketCreditNoteRepository */
    private $ticketCreditNoteRepository;

    public function __construct(TicketCreditNoteRepository $ticketCreditNoteRepo)
    {
        $this->ticketCreditNoteRepository = $ticketCreditNoteRepo;
    }

    /**
     * Display a listing of the TicketCreditNote.
     * GET|HEAD /ticketCreditNotes.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        /* @var TicketCreditNote $ticket_credit_notes */
        $ticket_credit_notes = TicketCreditNote::buscar($request);

        if ($request->filled('paginate')) {
            $ticket_credit_notes = $ticket_credit_notes->paginate($request->input('paginate'));
        } else {
            $ticket_credit_notes = $ticket_credit_notes->paginate(10);
        }

        return $this->sendResponse(new TicketCreditNoteCollection($ticket_credit_notes), 'Ticket Credit Notes retrieved successfully');
    }

    /**
     * Store a newly created TicketCreditNote in storage.
     * POST /ticketCreditNotes.
     *
     * @param CreateTicketCreditNoteAPIRequest $request
     *
     * @return Response
     */
    public function store(CreateTicketCreditNoteAPIRequest $request, $empresa_id)
    {
        $input = $request->all();

        $ticketCreditNote = $this->ticketCreditNoteRepository->create($input);

        return $this->sendResponse($ticketCreditNote->toArray(), 'Ticket Credit Note saved successfully');
    }

    /**
     * Display the specified TicketCreditNote.
     * GET|HEAD /ticketCreditNotes/{id}/company/{empresa_id}.
     *
     * @param APIRequest $request
     * @param  int $id
     * @param  int $empresa_id
     *
     * @return Response
     */
    public function show(APIRequest $request, $id, $empresa_id)
    {
        /** @var TicketCreditNote $ticketCreditNote */
        $ticketCreditNote = $this->ticketCreditNoteRepository->findWithoutFail($id);

        if (empty($ticketCreditNote)) {
            return $this->sendError('Ticket Credit Note not found');
        }

        return $this->sendResponse($ticketCreditNote->toArray(), 'Ticket Credit Note retrieved successfully');
    }

    /**
     * Update the specified TicketCreditNote in storage.
     * PUT/PATCH /ticketCreditNotes/{id}.
     *
     * @param  int $id
     * @param UpdateTicketCreditNoteAPIRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicketCreditNoteAPIRequest $request)
    {
        $input = $request->all();

        /** @var TicketCreditNote $ticketCreditNote */
        $ticketCreditNote = $this->ticketCreditNoteRepository->findWithoutFail($id);

        if (empty($ticketCreditNote)) {
            return $this->sendError('Ticket Credit Note not found');
        }

        $ticketCreditNote = $this->ticketCreditNoteRepository->update($input, $id);

        return $this->sendResponse($ticketCreditNote->toArray(), 'TicketCreditNote updated successfully');
    }

    /**
     * Remove the specified TicketCreditNote from storage.
     * DELETE /ticketCreditNotes/{id}.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        /** @var TicketCreditNote $ticketCreditNote */
        $ticketCreditNote = $this->ticketCreditNoteRepository->findWithoutFail($id);

        if (empty($ticketCreditNote)) {
            return $this->sendError('Ticket Credit Note not found');
        }

        $ticketCreditNote->delete();

        return $this->sendResponse($id, 'Ticket Credit Note deleted successfully');
    }
}
