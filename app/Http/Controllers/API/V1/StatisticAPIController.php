<?php

namespace App\Http\Controllers\API\V1;

use App\Events\DocumentCreated;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\Controller;
use App\Jobs\TestJob;
use App\Models\Contribuyente;
use App\Models\Documento;
use App\Models\Empresa;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Svg\Document;

class StatisticAPIController extends AppBaseController
{
    //

    public function index() {
        //dd(DB::table('documentos')->get());
        //$regiones = Permission::getPermissions();
        //return $this->sendResponse($regiones->toArray(), 'Regiones retrieved successfully');
        //TestJob::dispatch();
        //event(new DocumentCreated(Documento::find(61)));
    }
}
