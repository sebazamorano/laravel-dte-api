<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Http\Request;
use App\Models\SII\AcceptanceClaim;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class DetalleRespuestaDteXml.
 * @version February 24, 2019, 1:30 am -03
 *
 * @property bigInteger respuesta_dte_xml_id
 * @property tinyInteger tipoDte
 * @property int folio
 * @property date fchEmis
 * @property string rutEmisor
 * @property string rutRecep
 * @property decimal mntTotal
 * @property tinyInteger estado
 * @property string glosa
 * @property string digestValue
 * @property int file_id
 * @property RespuestaDteXml respuestaDteXml
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder create(array $attributes = [])
 * @method public Builder update(array $values)
 */
class DetalleRespuestaDteXml extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'detalles_respuestas_dtes_xmls';

    public $fillable = [
        'respuesta_dte_xml_id',
        'tipoDte',
        'folio',
        'fchEmis',
        'rutEmisor',
        'rutRecep',
        'mntTotal',
        'estado',
        'glosa',
        'digestValue',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'folio' => 'integer',
        'fchEmis' => 'date',
        'rutEmisor' => 'string',
        'rutRecep' => 'string',
        'glosa' => 'string',
        'digestValue' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [

    ];

    public function file()
    {
        return $this->belongsTo(\App\File::class);
    }

    public function respuestaDteXml()
    {
        return $this->belongsTo(\App\Models\RespuestaDteXml::class);
    }

    public function acceptanceClaims()
    {
        $acceptance_claims = AcceptanceClaim::where('folio', $this->folio)
            ->where('doc_type', $this->tipoDte)
            ->where('rut', $this->rutEmisor)
            ->where('company_id', $this->respuestaDteXml->empresa_id)->get();

        return $acceptance_claims;
    }

    public static function buscar(Request $request, $company_id = null)
    {
        $respuestas = self::join('respuestas_dtes_xmls', 'detalles_respuestas_dtes_xmls.respuesta_dte_xml_id', '=', 'respuestas_dtes_xmls.id')
            ->join('contribuyentes', 'detalles_respuestas_dtes_xmls.rutEmisor', '=', 'contribuyentes.rut')
            ->join('tipos_documentos', 'detalles_respuestas_dtes_xmls.tipoDte', '=', 'tipos_documentos.tipoDTE')
            ->orderBy('detalles_respuestas_dtes_xmls.id', 'DESC')
            ->select('detalles_respuestas_dtes_xmls.*', 'contribuyentes.razonSocial as razonSocialEmisor', 'tipos_documentos.nombre as tipoDteNombre');

        if ($company_id === null) {
            if ($request->is('api*')) {
                $company_id = $request->route('empresa_id');
            } else {
                $company_id = session('empresa_id');
            }
        }

        if ($company_id) {
            $respuestas = $respuestas->where('respuestas_dtes_xmls.empresa_id', $company_id);
        }

        if ($request->filled('fecha_emision_inicial')) {
            $respuestas = $respuestas->where('fchEmis', '>=', $request->input('fecha_emision_inicial'));
        }

        if ($request->filled('fecha_emision_final')) {
            $respuestas = $respuestas->where('fchEmis', '<=', $request->input('fecha_emision_final'));
        }

        if ($request->filled('tipoDte') && $request->input('tipoDte') != 'null') {
            $respuestas = $respuestas->where('detalles_respuestas_dtes_xmls.tipoDte', $request->input('tipoDte'));
        }

        if ($request->filled('folio') && $request->input('folio') != 'null') {
            $respuestas = $respuestas->where('detalles_respuestas_dtes_xmls.folio', $request->input('folio'));
        }

        if ($request->filled('rut')) {
            if ($request->filled('contenido') && $request->input('contenido') == 2) {
                $respuestas = $respuestas->where('rutEmisor', 'LIKE', '%'.$request->input('rut').'%');
            } else {
                $respuestas = $respuestas->where('rutEmisor', $request->input('rut'));
            }
        }

        return $respuestas;
    }
}
