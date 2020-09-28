<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent as Model;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Contribuyente.
 * @version January 3, 2019, 2:58 am -03
 *
 * @property string rut
 * @property string razonSocial
 * @property int numeroResolucion
 * @property string fechaResolucion
 * @property string mail
 * @property string url
 */
class Contribuyente extends Model
{
    use SoftDeletes, LadaCacheTrait;

    public $table = 'contribuyentes';

    public $fillable = [
        'rut',
        'razonSocial',
        'numeroResolucion',
        'fechaResolucion',
        'mail',
        'url',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'rut' => 'string',
        'razonSocial' => 'string',
        'numeroResolucion' => 'integer',
        'fechaResolucion' => 'string',
        'mail' => 'string',
        'url' => 'string',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'rut' => 'required|cl_rut|max:11',
        'razonSocial' => 'required|max:255',
        'numeroResolucion' => 'required|integer',
        'fechaResolucion' => 'required|max:11',
        'mail' => 'required|max:100',
        'url' => 'max:100',
    ];

    public static function actualizarRegistros(array $contribuyentes): void
    {
        DB::table('contribuyentes')->truncate();
        DB::beginTransaction();
        DB::disableQueryLog();
        $collection = collect($contribuyentes);
        //chunk == 1000 1minuto 32 segundos
        // chunk == 100 1 minuto 5 segundos en produccion 1 minuto y 24 segundos.
        // chunk == 50 1 minuto 10 segundos
        // chunk == 250 1 minuto 7 segundos
        $chunks = $collection->chunk(100);
        $chunks->toArray();

        foreach ($chunks as $chunk) {
            DB::table('contribuyentes')->insert($chunk->toArray());
        }

        DB::commit();
    }

    public static function csvToArray(): array
    {
        $i = 0;
        $separador_columnas = ';';
        $path = Storage::path('intercambio.csv');

        $str = Storage::get('intercambio.csv');
        $str = str_replace('"', '', $str);
        Storage::put('intercambio.csv', $str);
        // Abrir el archivo csv solo de lectura
        $archivo = fopen($path, 'r');

        $contribuyentes = [];
        $created_at = Carbon::now()->format('Y-m-d H:i:s');
        $updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $aux = 0;
        while ($row = fgetcsv($archivo, 0, $separador_columnas)) {
            if ($i > 0) {
                $url = substr(end($row), 0, 100);

                if($aux > 0){
                    $aux = 0;
                    $i++;
                    continue;
                }

                if ($row[0] == '76626040-3') {
                    $aux = 1;
                    $nombre = 'SERVICIOS DE ASEO Y MANTENCION LIMITADA EN LIQUIDACION';
                    $numero_resolucion = 99;
                    $fecha_resolucion = '21-10-2014';
                    $mail = 'facturacionmipyme2@sii.cl';
                    $url = '';
                }elseif($row[0] == '76328489-1') {
                    $aux = 1;
                    $nombre = 'SERVICIOS FINANCIEROS Y DE INTERMEDIACION COMERCIAL PEVERO LIMITADA';
                    $numero_resolucion = 99;
                    $fecha_resolucion = '21-10-2014';
                    $mail = 'FacturacionMIPYME@sii.cl';
                    $url = '';
                }elseif($row[0] == '76890862-1') {
                    $aux = 1;
                    $nombre = 'CELLUS BIOFACTORY SPA';
                    $numero_resolucion = 99;
                    $fecha_resolucion = '21-10-2014';
                    $mail = 'FacturacionMIPYME@sii.cl';
                    $url = '';
                }elseif($row[0] == '76049670-7') {
                    $aux = 1;
                    $nombre = 'SOC COMERCIAL DE SERVICIOS GASTRONOMICA EXP E IMP MAIHUE LIMITADA EN LIQUIDACION';
                    $numero_resolucion = 80;
                    $fecha_resolucion = '22-08-2014';
                    $mail = 'intercambio_portal@vesii.cl';
                    $url = '';
                }elseif($row[0] == '76303033-4') {
                    $aux = 1;
                    $nombre = 'INVERSIONES CAFETERIA Y GELATERIA SAN JOAQUIN SPA';
                    $numero_resolucion = 99;
                    $fecha_resolucion = '21-10-2014';
                    $mail = 'FacturacionMIPYME@sii.cl';
                    $url = '';
                }else{
                    if($row[0] == ''){
                        $aux = 1;
                        $i++;
                        continue;
                    }else{
                        $valid = Rut::parse($row[0])->quiet()->validate();
                        if(!$valid){
                            $aux = 1;
                            $i++;
                            continue;
                        }
                    }
                    $mail = substr(prev($row), 0 ,100);
                    $fecha_resolucion = substr(trim(prev($row)), 0, 11);
                    $numero_resolucion = substr(prev($row), 0, 3);
                    $nombre = '';

                    reset($row);
                    next($row);
                    do {
                        $nombre .= current($row);
                    } while (substr(next($row), 0, 3) !== $numero_resolucion);
                }

                $arreglo_temporal = [
                    'rut' => reset($row),
                    'razonSocial' => utf8_encode($nombre),
                    'numeroResolucion' => intval($numero_resolucion),
                    'fechaResolucion' => $fecha_resolucion,
                    'mail' => $mail,
                    'url' => utf8_encode($url),
                    'created_at'=> $created_at,
                    'updated_at'=> $updated_at
                ];

                array_push($contribuyentes, $arreglo_temporal);
            }
            $i++;
        }
        return $contribuyentes;
    }
}
