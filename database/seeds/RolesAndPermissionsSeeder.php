<?php

use App\Role;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'ver empresas', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear empresas', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar empresas', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar empresas', 'guard_name' => 'api']);
        Permission::create(['name' => 'subir logo empresa', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver sucursales', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear sucursales', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar sucursales', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar sucursales', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver localidades', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear localidades', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar localidades', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar localidades', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver boletas', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear boletas', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver pdf boletas', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver documentos', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear documentos', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar documentos', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver pdf documentos', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver emails', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear emails', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar emails', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar emails', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver empleados', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear empleados', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar empleados', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar empleados', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver entidades', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear entidades', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar entidades', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar entidades', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver notas de credito de boletas', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear notas de credito de boletas', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar notas de credito de boletas', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar notas de credito de boletas', 'guard_name' => 'api']);
        Permission::create(['name' => 'obtener listado dtes recibidos en SII', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver usuarios', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear usuarios', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar usuarios', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar usuarios', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar roles', 'guard_name' => 'api']);
        Permission::create(['name' => 'consultar folios autorizados', 'guard_name' => 'api']);
        Permission::create(['name' => 'solicitar timbraje', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver aceptacion/reclamos', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear aceptacion/reclamos', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar aceptacion/reclamos', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar aceptacion/reclamos', 'guard_name' => 'api']);
        Permission::create(['name' => 'enviar pdf dte', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver certificados digitales', 'guard_name' => 'api']);
        Permission::create(['name' => 'crear certificados digitales', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar certificados digitales', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar certificados digitales', 'guard_name' => 'api']);
        Permission::create(['name' => 'ver folios', 'guard_name' => 'api']);
        Permission::create(['name' => 'subir folios', 'guard_name' => 'api']);
        Permission::create(['name' => 'editar folios', 'guard_name' => 'api']);
        Permission::create(['name' => 'borrar folios', 'guard_name' => 'api']);
        Permission::create(['name' => 'obtener rcv', 'guard_name' => 'api']);
    }
}
