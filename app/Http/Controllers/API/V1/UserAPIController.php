<?php

namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\AppBaseController;
use App\Models\Empresa;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAPIController extends AppBaseController
{

    public function show(Request $request){
        return $request->user();
    }

    public function findUsers(Request $request)
    {
        /* @var Empresa $empresa */
        if($request->empresa){
            $empresa = Empresa::find($empresa);

            if (empty($empresa)) {
                return $this->sendError('Empresa not found');
            }

            if ($request->filled('paginate')) {
                $users = $empresa->employees()->paginate($request->input('paginate'));
            } else {
                $users = $empresa->employees()->paginate(10);
            }
        }else{
            $users = User::all();
        }


        return response()->json(['items'=> $users, 'totalCount' => count($users)], 200);
    }
}
