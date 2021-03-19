<?php

namespace App\Http\Controllers;

use App\Components\Certificador;
use App\Components\TipoArchivo;
use App\Jobs\ProcesarEnvioDteMultiple;
use App\Models\Caf;
use App\Models\Documento;
use App\Models\Empresa as Company;
use App\Models\SII\ConsumoFolios\FolioConsumption;
use App\Repositories\DocumentoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class CertificacionController extends Controller
{


    public function index(Company $company)
    {
        return view('certificacion.index')->with(compact('company'));
    }

    public function boletaAfectaForm(Company $company)
    {
        return view ('certificacion.boleta_afecta')->with(compact('company'));
    }

    public function boletaExentaForm(Company $company)
    {
        return view ('certificacion.boleta_exenta')->with(compact('company'));
    }

    public function boletaAfectaProcesar(Company $company, Request $request)
    {
        $certificador = new Certificador();
        $folios = $company->cafs()
            ->where('cafs.enUso', true)
            ->where('cafs.tipo_documento_id', 20)
            ->where('cafs.disponibles', '>=', 5)
            ->where('cafs.fechaVencimiento', '>=', Carbon::now())
            ->first();

        if(!$folios){
            request()->session()->flash('error-message', ['No existe folios disponibles!']);
            return redirect()->back();
        }

        $fecha = $request->input('date');
        $boletas = Certificador::setBoletasAfectas($fecha);
        $documentos = [];

        DB::beginTransaction();
        foreach($boletas as $input){
            $documento = $certificador->crearBoleta($input, $fecha, $company);
            $documentos[] = $documento->id;
        }

        DB::commit();
        ProcesarEnvioDteMultiple::dispatch($documentos, true);

        $certificador->crearRcf($company, $fecha, $documentos);
        request()->session()->flash('success-message', ['Xmls generados correctamente, seran enviados al correo de certificación!']);
        return redirect(route('companies.show', $company->id));
    }

    public function boletaExentaProcesar(Company $company, Request $request)
    {
        $certificador = new Certificador();
        $folios = $company->cafs()
            ->where('cafs.enUso', true)
            ->where('cafs.tipo_documento_id', 21)
            ->where('cafs.disponibles', '>=', 3)
            ->where('cafs.fechaVencimiento', '>=', Carbon::now())
            ->first();

        if(!$folios){
            request()->session()->flash('error-message', ['No existe folios disponibles!']);
            return redirect()->back();
        }

        $fecha = $request->input('date');
        $boletas = Certificador::setBoletasExentas($fecha);
        $documentos = [];

        DB::beginTransaction();
        foreach($boletas as $input){
            $documento = $certificador->crearBoleta($input, $fecha, $company);
            $documentos[] = $documento->id;
        }

        DB::commit();
        ProcesarEnvioDteMultiple::dispatch($documentos, true);

        $certificador->crearRcf($company, $fecha, $documentos);
        request()->session()->flash('success-message', ['Xmls generados correctamente, seran enviados al correo de certificación!']);
        return redirect(route('companies.show', $company->id));
    }
}
