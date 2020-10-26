<?php

namespace App\Http\Controllers\API\V1;

use App\User;
use Response;
use Carbon\Carbon;
use App\Models\Empresa;
use Illuminate\Support\Str;
use App\Http\Request\APIRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\AppBaseController;
use App\Http\Request\API\CreateUserAPIRequest;

/**
 * Class CompanyUserAPIController.
 */
class CompanyUserAPIController extends AppBaseController
{

    public function __construct()
    {
    }

    /**
     * Display a listing of the Empresa.
     * GET|HEAD /empresas/{empresa_id}/users.
     *
     * @param APIRequest $request
     * @param int $empresa_id
     * @return Response
     */
    public function index(APIRequest $request, $empresa_id)
    {
        /* @var Empresa $empresa */
        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        if ($request->filled('paginate')) {
            $users = $empresa->employees()->paginate($request->input('paginate'));
        } else {
            $users = $empresa->employees()->paginate(10);
        }

        return $this->sendResponse($users->toArray(), 'Roles retrieved successfully');
    }

    /**
     * Display a listing of the Empresa.
     * GET|HEAD /empresas/{empresa_id}/users/{id}.
     *
     * @param APIRequest $request
     * @param int $empresa_id
     * @param User $user
     * @return Response
     */
    public function show(APIRequest $request, $empresa_id, User $user)
    {
        /* @var Empresa $empresa */
        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $user = $empresa->employees()->where('user_id', $user->id)->first();

        if (! $user) {
            return $this->sendError('User not found or not assigned to the company');
        }

        $user_array = $user->toArray();
        $user_array['roles'] = $user->roles($empresa->id)->get();

        return $this->sendResponse($user_array, 'User retrieved successfully');
    }

    /**
     * Store a newly created Employee Empresa in storage.
     * POST /empresas/{empresa_id}/users.
     * @param CreateUserAPIRequest $request
     * @param int $empresa_id
     *
     * @return Response
     * @throws
     */
    public function store(CreateUserAPIRequest $request, $empresa_id)
    {
        DB::beginTransaction();

        /* @var Empresa $empresa */
        /* @var User $employee */
        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $user = User::where('email', $request->input('email'))->first();

        if ($user) {
            $boolean = $empresa->employees()->where('user_id', $user->id)->first();

            if (! $boolean) {
                $empresa->employees()->attach($user->id, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            }

            $employee = $user;
        } else {
            $input = $request->only(['name', 'email']);
            $input['password'] = Hash::make(Str::random(8));

            if($request->filled('password')){
                $input['password'] = Hash::make($request->input('password'));
            }

            $this->validate($request, ['email' => 'required|email|unique:users|string|min:5']);

            $employee = $empresa->employees()->create($input, ['created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
            if (! $employee) {
                DB::rollBack();

                return $this->sendError('User not created');
            }
        }

        DB::commit();
        DB::table('role_tenant_user')->where('tenant_id', $empresa->id)->where('user_id', $employee->id)->delete();
        $roles = $request->input('roles', []);
        $employee->assignRoleToTenant($roles, $empresa->id);

        return $this->sendResponse($employee->toArray(), 'User saved successfully');
    }

    /**
     * Store a newly created Empresa in storage.
     * PUT/PATCH /empresas/{empresa_id}/users/{user}.
     * @param APIRequest $request
     * @param int $empresa_id
     * @param User $user
     *
     * @return Response
     * @throws
     */
    public function update(APIRequest $request, $empresa_id, User $user)
    {
        /* @var Empresa $empresa */
        /* @var User $selected_user */
        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return $this->sendError('Empresa not found');
        }

        $selected_user = $empresa->employees()->where('user_id', $user->id)->first();

        if (! $selected_user) {
            return $this->sendError('User not an employee');
        }

        /* @var Empresa $empresas */
        $input = $request->only(['email']);

        if($request->filled('password')){
            $input['password'] = Hash::make($request->input('password'));
        }

        if ($selected_user->email !== $request->input('email')) {
            $this->validate($request, ['email' => 'required|email|unique:users|string|min:5']);
        }else{
            unset($input['email']);
        }

        $updatedUser = $selected_user->update($input);
        if (! $updatedUser) {
            return $this->sendError('User not updated');
        }

        DB::table('role_tenant_user')->where('tenant_id', $empresa->id)->where('user_id', $selected_user->id)->delete();
        $roles = $request->input('roles', []);

        $selected_user->assignRoleToTenant($roles, $empresa->id);

        return $this->sendResponse($selected_user->toArray(), 'User saved successfully');
    }
}
