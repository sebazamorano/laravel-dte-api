<?php

namespace App\Http\Controllers\API\V1;

use App\Role;
use Response;
use App\Models\Empresa;
use App\Http\Request\APIRequest;
use App\Http\Controllers\AppBaseController;

/**
 * Class CompanyRoleAPIController.
 */
class CompanyRoleAPIController extends AppBaseController
{

    public function __construct()
    {
    }

    /////////////////////////Roles

    /**
     * Display a listing of the Empresa.
     * GET|HEAD /empresas/{empresa_id}/roles.
     *
     * @param APIRequest $request
     * @param int $empresa_id
     *
     * @return Response
     */
    public function index(APIRequest $request, $empresa_id)
    {
        /* @var Empresa $empresa */
        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $roles = $empresa->companyRoles();

        if ($request->filled('all') && $request->input('all') == '1') {
            $roles = $roles->get();
        } else {
            if ($request->filled('paginate')) {
                $roles = $roles->paginate($request->input('paginate'));
            } else {
                $roles = $roles->paginate(10);
            }
        }

        return $this->sendResponse($roles->toArray(), 'Roles retrieved successfully');
    }

    /**
     * Display a listing of the Empresa.
     * GET|HEAD /empresas/{empresa}/roles/{role}.
     *
     * @param APIRequest $request
     * @param int $empresa_id
     * @param int $id
     * @return Response
     */
    public function show(APIRequest $request, $empresa_id, $id)
    {
        /* @var Empresa $empresa */
        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $role = $empresa->companyRoles($id)->first();

        if (! $role) {
            return $this->sendError('Role doesnt exist or not assigned to company');
        }

        return $this->sendResponse($role->toArray(), 'Roles retrieved successfully');
    }

    /**
     * Store a newly created Role in the company.
     * POST /empresas/{empresa}/roles.
     *
     * @param APIRequest $request
     * @param int $empresa_id
     * @return Response
     * @throws
     */
    public function store(APIRequest $request, $empresa_id)
    {
        /* @var Empresa $empresas */
        $input = $request->only(['name', 'guard_name']);
        $input['name'] = '['.$empresa_id.']'.trim($input['name']);
        $request->merge(['name' => $input['name']]);

        $this->validate($request, ['name' => 'required|unique:roles|string|min:5',
            'guard_name' => 'required|min:1', ]);

        $role = Role::create($input);
        if (! $role) {
            return $this->sendError('Role not created');
        }

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->sendResponse($role->toArray(), 'Role saved successfully');
    }

    /**
     * Store a newly created Empresa in storage.
     * PUT/PATCH /empresas/{empresa}/roles/{role}permissions.
     * @param APIRequest $request
     * @param int $empresa_id
     * @param Role $role
     *
     * @return Response
     * @throws
     */
    public function update(APIRequest $request, $empresa_id, Role $role)
    {
        /* @var Empresa $empresa */
        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $input = $request->only(['name', 'guard_name']);
        $input['name'] = '['.$empresa_id.']'.trim($input['name']);
        $request->merge(['name' => $input['name']]);

        if (! $empresa->companyRoles($role->id)->first()) {
            if (! $role) {
                return $this->sendError('Role doesnt exist or not assigned to company');
            }
        }

        if ($role->name !== $request->input('name')) {
            $this->validate($request, ['name' => 'required|unique:roles|string|min:5',
                'guard_name' => 'required|min:1', ]);

            $updatedRole = $role->update($input);
            if (! $updatedRole) {
                return $this->sendError('Role not updated');
            }
        }

        $permissions = $request->input('permissions', []);
        $role->syncPermissions($permissions);
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->sendResponse($role->toArray(), 'Role saved successfully');
    }

    /**
     * Delete a role of the company storage.
     * DELETE /empresas/{empresa_id}/roles/{role}.
     *
     * @param APIRequest $request
     * @param int $empresa_id
     * @param Role $role
     *
     * @return Response
     * @throws \Exception
     */
    public function destroy(APIRequest $request, $empresa_id, Role $role)
    {
        /* @var Empresa $empresa */
        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $role_to_delete = $empresa->companyRoles($role->id);

        if (empty($role_to_delete)) {
            return $this->sendError('Role not found');
        }

        if (empty($empresa)) {
            return $this->sendError('Company not found');
        }

        $role->delete();
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        return $this->sendResponse($role, 'Role deleted successfully');
    }
}
