<?php

namespace App\Console\Commands;

use App\Jobs\RegenerarXmlDte;
use App\Models\Documento;
use Illuminate\Console\Command;

class FixDte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:fix-dte {tipo_documento_id} {empresa_id} {from} {to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando se encarga de re-regenerar los XML de acuerdo a parametros de entrada (obligatorios)';

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

        $tipo_documento_id = $this->argument('tipo_documento_id');
        $empresa_id = $this->argument('empresa_id');
        $from = $this->argument('from');
        $to =  $this->argument('to');

        $documentos = Documento::where('tipo_documento_id', $tipo_documento_id)
            ->where('empresa_id', $empresa_id)
            ->where('fechaEmision', '>=', $from)
            ->where('fechaEmision', '<=', $to)
            ->where('glosaEstadoSii', 'DTE Rechazado [ESQUEMA]')
            ->pluck('id');

        foreach($documentos as $documento){
            RegenerarXmlDte::dispatch($documento);
        }
    }
}
