<?php

namespace App\Repositories;

use App\Models\TicketCreditNote;
use App\Repositories\Repository as BaseRepository;

/**
 * Class TicketCreditNoteRepository.
 * @version April 23, 2019, 2:38 am -04
 *
 * @method TicketCreditNote findWithoutFail($id, $columns = ['*'])
 * @method TicketCreditNote find($id, $columns = ['*'])
 * @method TicketCreditNote first($columns = ['*'])
 */
class TicketCreditNoteRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'empresa_id',
        'documento_id',
        'montoNeto',
        'montoExento',
        'iva',
        'tasaIva',
        'montoTotal',
        'folioBoleta',
        'folioNotaCredito',
    ];

    /**
     * Configure the Model.
     **/
    public function model()
    {
        return TicketCreditNote::class;
    }
}
