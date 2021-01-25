<?php

namespace App\Console\Commands;

use App\Jobs\ProcesarEnvioDteMultiple;
use App\Models\Documento;
use App\Models\Empresa;
use Illuminate\Console\Command;

class UploadTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:upload-tickets {company_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando se encarga de subir las ultimas (segun la cantidad seteada) boletas de cada empresa, adicionalmente puedes indicar el id de la empresa';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $company_id = $this->argument('company_id');

        if(isset($company_id)){
            $empresas = Empresa::where('id', $company_id)->get();
        }else{
            $empresas = Empresa::all();
        }

        foreach($empresas as $empresa){
            $this->info('Empresa [' . $empresa->rut . ']' . $empresa->razonSocial . ' entrando al loop de UploadTickets');
            $ids = Documento::where(function($query){
                $query->where('tipo_documento_id', 20)
                    ->orWhere('tipo_documento_id',  21);
                })
                ->where('fechaEmision', '>', '2020-12-31')
                ->where('empresa_id', $empresa->id)
                ->where('IO', 0)
                ->where(function($query){
                    $query->where('glosaEstadoSii', 'DTE No Recibido')->orWhereNull('glosaEstadoSii');
                })
                ->limit(config('dte.max_quantity_packing'))
                ->pluck('id');

            ProcesarEnvioDteMultiple::dispatch($ids);
        }
    }
}
