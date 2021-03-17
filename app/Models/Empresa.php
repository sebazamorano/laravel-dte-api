<?php

namespace App\Models;

use App\File;
use App\Mail\Information;
use App\Role;
use App\Traits\TenantBase;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Exceptions\TenantAlreadyExist;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Contracts\Spatie\Tenant as TenantContract;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\RefreshesPermissionCache;
use Spiritix\LadaCache\Database\LadaCacheTrait;

/**
 * Class Empresa.
 * @version October 1, 2018, 4:58 am UTC
 *
 * @property int id
 * @property string rut
 * @property string razonSocial
 * @property string direccion
 * @property int region_id
 * @property int provincia_id
 * @property int comuna_id
 * @property string giro
 * @property string contactoSii
 * @property string passwordContactoSii
 * @property string contactoEmpresas
 * @property string passwordContactoEmpresas
 * @property string servidorSmtp
 * @property date fechaResolucion
 * @property int numeroResolucion
 * @property date fechaResolucionBoleta
 * @property int numeroResolucionBoleta
 * @property string nombreLogotipo
 * @property string archivoLogotipo
 * @property int esEmisor
 * @property int esReceptor
 * @property int reglasNegocio
 * @property int logo_id
 * @property File logo
 * @property Comuna comuna
 * @property-read Provincia provincia
 * @property-read Role companyRoles[]
 * @property-read Caf cafs
 * @property-read Sucursal sucursales[]
 * @property ActividadEconomicaEmpresa actividadesEconomicasEmpresas[]
 * @method static Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static Builder create(array $attributes = [])
 * @method public Builder update(array $values)
 */
class Empresa extends Model implements TenantContract
{
    use SoftDeletes, LadaCacheTrait, TenantBase, RefreshesPermissionCache;

    public $table = 'empresas';

    public $fillable = [
        'rut',
        'razonSocial',
        'direccion',
        'region_id',
        'provincia_id',
        'comuna_id',
        'giro',
        'contactoSii',
        'passwordContactoSii',
        'contactoEmpresas',
        'passwordContactoEmpresas',
        'servidorSmtp',
        'fechaResolucion',
        'numeroResolucion',
        'fechaResolucionBoleta',
        'numeroResolucionBoleta',
        'nombreLogotipo',
        'archivoLogotipo',
        'esEmisor',
        'esReceptor',
        'reglasNegocio',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'rut' => 'string',
        'razonSocial' => 'string',
        'direccion' => 'string',
        'region_id' => 'integer',
        'provincia_id' => 'integer',
        'comuna_id' => 'integer',
        'giro' => 'string',
        'contactoSii' => 'string',
        'passwordContactoSii' => 'string',
        'contactoEmpresas' => 'string',
        'passwordContactoEmpresas' => 'string',
        'servidorSmtp' => 'string',
        'fechaResolucion' => 'date:Y-m-d',
        'numeroResolucion' => 'integer',
        'fechaResolucionBoleta' => 'date:Y-m-d',
        'numeroResolucionBoleta' => 'integer',
        'nombreLogotipo' => 'string',
        'archivoLogotipo' => 'string',
        'esEmisor' => 'integer',
        'esReceptor' => 'integer',
        'reglasNegocio' => 'integer',
    ];

    /**
     * Validation rules.
     *
     * @var array
     */
    public static $rules = [
        'rut' => 'max:10|required|cl_rut',
        'razonSocial' => 'max:150|required',
        'direccion' => 'max:70|required',
        'region_id' => 'required|min:1',
        'provincia_id' => 'required|min:1',
        'comuna_id' => 'required|min:1',
        'giro' => 'max:80|required',
        'contactoSii' => 'max:80',
        'passwordContactoSii' => 'max:100',
        'contactoEmpresas' => 'max:80',
        'passwordContactoEmpresas' => 'max:100',
        'servidorSmtp' => 'max:255',
        'fechaResolucion' => 'date',
        'numeroResolucion' => 'integer',
        'fechaResolucionBoleta' => 'date',
        'numeroResolucionBoleta' => 'integer',
    ];

    public static $labels = [
        'id' => 'ID',
        'name' => 'Razón Social',
        'fantasy_name' => 'Nombre Fantasia',
        'identity_card' => 'RUT',
        'phone' => 'Telefono',
        'line' => 'Giro',
        'client' => 'Cliente',
        'provider' => 'Proveedor',
        'created_at' => 'Fecha creación',
        'updated_at' => 'Fecha actualización',
        'deleted_at' => 'Fecha eliminación',
    ];

    /**
     * The data of the comuna referenced to the company.
     */
    public function comuna()
    {
        return $this->belongsTo(\App\Models\Comuna::class);
    }

    /**
     * The economic activities that belongs to the enterprise.
     */
    public function provincia()
    {
        return $this->belongsTo(\App\Models\Provincia::class);
    }

    /**
     * The economic activities that belongs to the enterprise.
     */
    public function region()
    {
        return $this->belongsTo(\App\Models\Region::class);
    }

    /**
     * obtener las sucursales de la empresa.
     */
    public function sucursales()
    {
        return $this->hasMany(\App\Models\Sucursal::class);
    }

    /**
     * obtener las sucursales de la empresa.
     */
    public function companyBranchOffices()
    {
        return $this->hasMany(\App\Models\Sucursal::class, 'empresa_id', 'id');
    }

    /**
     * The economic activities that belongs to the company.
     */
    public function documentosAutorizados()
    {
        return $this->belongsToMany(\App\Models\TipoDocumento::class, 'documentos_autorizados');
    }

    /**
     * The economic activities that belongs to the company.
     */
    public function actividadesEconomicasEmpresas()
    {
        return $this->belongsToMany(\App\Models\ActividadEconomica::class, 'actividades_economicas_empresas');
    }

    /**
     * The certificates that belongs to the company.
     */
    public function certificados()
    {
        return $this->hasMany(\App\Models\CertificadoEmpresa::class);
    }

    /**
     * The parameters that belongs to the company.
     */
    public function parametros()
    {
        return $this->hasMany(\App\Models\EmpresaParametro::class);
    }

    /**
     * The caf that belongs to the company.
     */
    public function cafs()
    {
        return $this->hasMany(\App\Models\Caf::class);
    }

    public static function verificarFormatoCertificado(Request $request)
    {
        if (! in_array($request->file('original')->getClientOriginalExtension(), ['pfx', 'p12'])) {
            return false;
        }

        return true;
    }

    public static function contrasenaCertificadoEsValida($p12worked)
    {
        if (! $p12worked) {
            return false;
        }

        return true;
    }

    public static function parseCertificado(Request $request)
    {
        if (! self::verificarFormatoCertificado($request)) {
            throw new HttpResponseException(response()->json([
                'message' => '422 error',
                'errors' => ['original'=>['original debe ser un archivo con formato : p12, pfx.']],
                'status_code' => 422,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }

        $p12worked = openssl_pkcs12_read(file_get_contents($request->file('original')->getPathname()), $p12, $request->password);

        if (! self::contrasenaCertificadoEsValida($p12worked)) {
            throw new HttpResponseException(response()->json([
                'message' => '422 error',
                'errors' => ['original'=>['No se puede leer el almacén de certificados', openssl_error_string()]],
                'status_code' => 422,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }

        return $p12;
    }

    public function actualizarSubjectCertificado()
    {
    }

    public function actualizarIssuerCertificado()
    {
    }

    public function documentoEstaAutorizado($td)
    {
        $autorizado = self::documentosAutorizados()->where('tipo_documento_id', \App\Components\TipoDocumento::getTipoDocumentoId($td))->first();

        if (empty($autorizado)) {
            return false;
        }

        return true;
    }

    /**
     * The users that belongs to the company.
     */
    public function employees()
    {
        return $this->belongsToMany(\App\User::class, 'employees', 'company_id')->withPivot('branch_office_id');
    }

    public function companyRoles($role_id = null)
    {
        $role = Role::with('permissions')->where('name', 'like', '['.$this->id.']%');

        if ($role_id) {
            $role = $role->where('id', $role_id);
        }

        return $role;
    }

    public function isEmployee($user_id)
    {
        $employee = $this->companyUsers($user_id);

        if (! $employee) {
            return false;
        }

        return true;
    }

    public function companyUsers($user_id = null)
    {
        $employees = $this->employees();

        if ($user_id) {
            $employees = $employees->where('user_id', $user_id)->first();
        }

        return $employees;
    }

    public function descargarCertificadoPem(): array
    {
        /* @var CertificadoEmpresa $certificado */
        $certificado = $this->certificados()->where('enUso', 1)->first();
        $nombre_cert = uniqid().'.pem';
        Storage::put($nombre_cert, $certificado->pemFile->content());
        $path_certificado = Storage::path($nombre_cert);

        return [$nombre_cert, $path_certificado, $certificado->password];
    }

    public function borrarCertificado($path_certificado)
    {
        Storage::delete($path_certificado);
    }

    public function logo()
    {
        return $this->belongsTo(File::class);
    }

    public function obtenerCertificadoEnUso()
    {
        $certificado = $this->certificados()->where('enUso', 1)->first();

        if($certificado === null){
            $details = [
                'title' => '[CERTIFICADO NO CARGADO] EMPRESA NO PODRA EJECUTAR ACCIONES',
                'message' =>  'La empresa no tiene un certificado digital configurado, por lo que no podra ejecutar ninguna acción relacionada con el SII',
            ];
            Mail::to(config('dte.cron_mail'))->send(new Information($details));
        }

        return $certificado;
    }
}
