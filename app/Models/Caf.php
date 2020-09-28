<?php

namespace App\Models;

use App\Components\Sii;
use Eloquent as Model;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Exceptions\HttpResponseException;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Caf.
 * @version January 11, 2019, 12:51 pm -03
 *
 * @property int id
 * @property int empresa_id
 * @property int tipo_documento_id
 * @property int file_id
 * @property int folioDesde
 * @property int folioHasta
 * @property int folioUltimo
 * @property date fa
 * @property date fechaVencimiento
 * @property int disponibles
 * @property bool enUso
 * @property bool completado
 * @property \App\File archivo
 * @property \App\Models\Empresa empresa
 */
class Caf extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'cafs';
    protected $with = ['archivo', 'tipo_documento'];

    public $fillable = [
        'empresa_id',
        'tipo_documento_id',
        'file_id',
        'folioDesde',
        'folioHasta',
        'folioUltimo',
        'fa',
        'fechaVencimiento',
        'disponibles',
        'enUso',
        'completado',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'empresa_id' => 'integer',
        'tipo_documento_id' => 'integer',
        'file_id' => 'integer',
        'folioDesde' => 'integer',
        'folioHasta' => 'integer',
        'folioUltimo' => 'integer',
        'fa' => 'date',
        'fechaVencimiento' => 'date',
        'disponibles' => 'integer',
        'enUso' => 'boolean',
        'completado' => 'boolean',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'empresa_id' => 'required',
        'tipo_documento_id' => 'required',
        'file_id' => 'required',
        'folioDesde' => 'required',
        'folioHasta' => 'required',
        'folioUltimo' => 'required',
        'fa' => 'required|date',
        'fechaVencimiento' => 'required|after:fa',
    ];

    public function empresa()
    {
        return $this->belongsTo(\App\Models\Empresa::class);
    }

    public static function verificarEmpresa(int $empresaRequest, Empresa $empresaCAF)
    {
        if ($empresaRequest != $empresaCAF->id) {
            throw new HttpResponseException(response()->json([
                'message' => 'error en empresa',
                'errors' => ['empresa_id'=>['CAF no corresponde a la empresa']],
                'status_code' => 401,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }
    }

    public function archivo()
    {
        return $this->belongsTo(\App\File::class, 'file_id');
    }

    public function tipo_documento()
    {
        return $this->belongsTo(TipoDocumento::class, 'tipo_documento_id');
    }

    public static function validarUnique($caf_data)
    {
        /* @var Empresa $empresa */
        $empresa = Empresa::whereRut($caf_data['rut'])->first();
        $input = [];
        $input['tipo_documento_id'] = \App\Components\TipoDocumento::getTipoDocumentoId($caf_data['td']);
        $messages = [
            'unique' => 'Rango de folios para el tipo de documentos ya existe en los registros de la empresa.',
        ];
        $rules = [
            'tipo_documento_id' => Rule::unique('cafs')->where(function ($query) use ($caf_data, $empresa) {
                return $query->where('folioDesde', $caf_data['desde'])->where('folioHasta', $caf_data['hasta'])->where('empresa_id', $empresa->id);
            }),

        ];
        Validator::make($input, $rules, $messages)->validate();
    }

    /**
     * Entrega un arreglo con los datos del caf rut, td, desde, hasta, fa.
     *
     * @param    array  $input el arreglo previo para procesar la informacion
     * @author Joaquín Gamboa Figueroa, joaquin.gamboaf@gmail.com
     * @return   array['folioDesde', 'folioHasta', 'fa', 'fechaVencimiento', 'file_id', 'folioUltimo']
     */
    public static function prepararInformacionParaCrear(array $input, array $caf_data)
    {
        $input['folioDesde'] = $caf_data['desde'];
        $input['folioHasta'] = $caf_data['hasta'];
        $input['fa'] = $caf_data['fa'];
        $input['fechaVencimiento'] = date('Y-m-d', strtotime('+6 months', strtotime($input['fa'])));
        $input['tipo_documento_id'] = \App\Components\TipoDocumento::getTipoDocumentoId($caf_data['td']);

        if (! empty($input['folioActual']) && $input['folioActual'] != 0) {
            $input['folioUltimo'] = $input['folioActual'] - 1;
        } else {
            $input['folioUltimo'] = $input['folioDesde'] - 1;
        }

        $input['disponibles'] = $input['folioHasta'] - $input['folioUltimo'];
        $input['completado'] = 0;

        unset($input['folioActual']);
        unset($input['caf']);

        return $input;
    }

    public function solicitarFolio($tipo_documento_id, Empresa $empresa, $nroFolio = null)
    {
        /* @var Caf $caf */
        $parametros = $empresa->parametros()->whereNombre('folios_automaticos')->first();

        if ((! empty($parametros) && $parametros->valor == '0') || $nroFolio !== null ) {

            $existe = Documento::folioExiste($nroFolio, $tipo_documento_id, $empresa->id);

            if($existe){
                return false;
            }

            $caf = $empresa->cafs()->where('tipo_documento_id', $tipo_documento_id)->where('completado', 0)->where(function ($query) use ($nroFolio){
                $query->where('folioDesde', '<=', $nroFolio);
                $query->where('folioHasta', '>=', $nroFolio);
            })->first();

            if (empty($caf)) {
                return false;
            }

            $caf->disponibles -= 1;

            if($caf->disponibles == 0){
                $caf->completado = 1;
            }

            $caf->update();
        } else {
            $caf = $empresa->cafs()->whereCompletado(0)->where('enUso', 1)->where('tipo_documento_id', $tipo_documento_id)->first();

            if (empty($caf)) {
                return false;
            }

            $caf->folioUltimo += 1;

            $existe = Documento::folioExiste($caf->folioUltimo, $tipo_documento_id, $empresa->id);

            if($existe){
                return false;
            }

            $caf->disponibles = $caf->folioHasta - $caf->folioUltimo;

            if ($caf->folioUltimo == $caf->folioHasta) {
                $caf->completado = 1;
            }

            $caf->update();
        }

        return $caf;
    }
}
