<?php

namespace App\Http\Controllers\API\V1;

use Spatie\Permission\Models\Permission;
use App\Http\Controllers\AppBaseController;

class PermissionAPIController extends AppBaseController
{
    public function index()
    {
        $permissions = Permission::all();
        return $this->sendResponse($permissions->toArray(), 'Permissions retrieved successfully');
    }
}
