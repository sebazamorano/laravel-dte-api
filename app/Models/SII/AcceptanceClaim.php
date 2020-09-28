<?php

namespace App\Models\SII;

use Eloquent as Model;
use App\Components\Sii;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @SWG\Definition(
 *      definition="AcceptanceClaim",
 *      required={"company_id", "rut", "doc_type", "folio", "action", "event_date"},
 *      @SWG\Property(
 *          property="id",
 *          description="id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="company_id",
 *          description="company_id",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="rut",
 *          description="rut",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="doc_type",
 *          description="doc_type",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="folio",
 *          description="folio",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="action",
 *          description="action",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="response_code",
 *          description="response_code",
 *          type="integer",
 *          format="int32"
 *      ),
 *      @SWG\Property(
 *          property="response_description",
 *          description="response_description",
 *          type="string"
 *      ),
 *      @SWG\Property(
 *          property="event_date",
 *          description="event_date",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="created_at",
 *          description="created_at",
 *          type="string",
 *          format="date-time"
 *      ),
 *      @SWG\Property(
 *          property="updated_at",
 *          description="updated_at",
 *          type="string",
 *          format="date-time"
 *      )
 * )
 */
/**
 * Class AcceptanceClaim.
 * @version June 1, 2019, 4:45 pm GMT-3
 *
 * @property int id
 * @property int company_id
 * @property string rut
 * @property int doc_tye
 * @property int folio
 * @property string action
 * @property int response_code
 * @property string response_description
 * @property string event_date
 */
class AcceptanceClaim extends Model
{
    use SoftDeletes;

    public $table = 'sii_acceptance_claims';

    public $fillable = [
        'company_id',
        'rut',
        'doc_type',
        'folio',
        'action',
        'response_code',
        'response_description',
        'event_date',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'company_id' => 'integer',
        'rut' => 'string',
        'doc_type' => 'integer',
        'folio' => 'integer',
        'action' => 'string',
        'response_code' => 'integer',
        'response_description' => 'string',
        'event_date' => 'datetime',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'company_id' => 'required|exists:empresas,id',
        'rut' => 'required|string|max:11|cl_rut',
        'doc_type' => 'required|numeric|in:33,34',
        'folio' => 'required|numeric',
        'action' => 'required|string|max:3|in:ACD,RCD,ERM,RFP,RFT',
        'response_code' => 'nullable',
        'response_description' => 'string|max:70|nullable',
        'event_date' => 'nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     **/
    public function company()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function isUnique($data)
    {
        if (! isset($data)) {
            $data = [
                'company_id' => $this->company_id,
                'rut' => $this->rut,
                'doc_type' => $this->doc_tye,
                'folio' => $this->folio,
                'action' => $this->action,
            ];
        }

        $action = self::where('company_id', $data['company_id'])
            ->where('rut', $data['rut'])
            ->where('doc_type', $data['doc_type'])
            ->where('folio', $data['folio'])
            ->where('action', $data['action'])->first();

        if ($action) {
            return false;
        }

        return true;
    }

    public static function saveActionSii($data)
    {
        $company = Empresa::find($data['company_id']);
        $siiComponent = new Sii($company);
        $result = $siiComponent->documentActionToSii($data);

        return $result;
    }
}
