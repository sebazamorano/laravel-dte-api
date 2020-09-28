<?php

namespace App;

use App\Models\Empresa;
use App\Traits\HasTenants;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Spiritix\LadaCache\Database\LadaCacheTrait;

class User extends Authenticatable
{
    use Notifiable, LadaCacheTrait, HasTenants, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isSuperAdmin()
    {
        return $this->admin == 1 ? true : false;
    }

    /**
     * The companys that belong to the user.
     */
    public function empresas()
    {
        return $this->belongsToMany(\App\Models\Empresa::class, 'employees', 'user_id', 'company_id');
    }

    /**
    devuelve la lista de empresas en la cual labora el usuario que ha iniciado sesion
     */
    public function misEmpresas($paginate = 0, $cantidad_paginacion = 10)
    {
        $booleanResult = \Auth::user()->isSuperAdmin();

        if ($booleanResult) {
            if ($paginate == 1) {
                $list = Empresa::paginate($cantidad_paginacion);
            } else {
                $list = Empresa::all();
            }

            $result = $list;
        } else {
            if ($paginate == 1) {
                $list = \Auth::user()->empresas->paginate($cantidad_paginacion);
            } else {
                $list = \Auth::user()->empresas;
            }

            $result = $list;
        }

        return $result;
    }

    public function asignarEmpresa($empresa)
    {
        session(['empresa_id' => $empresa->id]);
        session(['empresa_razon_social' => $empresa->razonSocial]);
        session(['empresa_rut' => $empresa->rut]);
        //app()['cache']->forget('spatie.permission.cache');
    }

    public function roles($company_id)
    {
        return Role::with('permissions')->where('name', 'like', '['.$company_id.']%')
            ->join(config('permission.table_names.role_tenant_user'), 'role_tenant_user.role_id', '=', 'roles.id')
            ->where(config('permission.table_names.role_tenant_user').'.user_id', $this->id);
    }
}
