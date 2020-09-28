<?php

namespace App\Models\SII;

use App\Components\Sii;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class DocumentInformation.
 * @version June 1, 2019, 4:45 pm GMT-3
 *
 * @property int id
 * @property int company_id
 * @property string rut
 * @property int doc_tye
 * @property int folio
 * @property string reception_date
 * @property int transferable_response_code
 * @property string transferable_description
 */
class DocumentInformation extends Model
{
    use SoftDeletes;

    public $table = 'sii_documents_information';

    public $fillable = [
        'company_id',
        'rut',
        'doc_type',
        'folio',
        'reception_date',
        'transferable_response_code',
        'transferable_description',
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
        'reception_date' => 'datetime',
        'transferable_response_code' => 'integer',
        'transferable_description' => 'string',
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
        'folio' => 'required|integer',
        'reception_date' => 'nullable',
        'transferable_response_code' => 'integer',
        'transferable_description' => 'string|max:70|nullable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function company()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function listHistoryDocEvents()
    {
    }

    public static function getModel($data)
    {
        $model = self::where('company_id', $data['company_id'])
            ->where('rut', $data['rut'])
            ->where('doc_type', $data['doc_type'])
            ->where('folio', $data['folio'])->first();

        if (! $model) {
            $model = self::getReceptionDate($data);
        }

        return $model;
    }

    public static function getReceptionDate($data)
    {
        $company = Empresa::find($data['company_id']);
        $siiComponent = new Sii($company);

        $data_ws = [];
        $data_ws['folio'] = $data['folio'];
        $data_ws['tipoDoc'] = $data['doc_type'];
        $rut_array = Sii::splitRutOnTwo($data['rut']);
        $data_ws = array_merge($data_ws, $rut_array);

        $result = $siiComponent->getDocumentReceptionDate($data_ws);

        if ($result) {
            $date = \DateTime::createFromFormat('d-m-Y H:i:s', $result);
            $data['reception_date'] = $date->format('Y-m-d H:i:s');
            $document_info = self::create($data);

            return $document_info;
        }

        return false;
    }
}
