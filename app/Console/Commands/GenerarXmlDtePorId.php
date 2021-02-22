<?php

namespace App\Console\Commands;

use App\Components\TipoArchivo;
use App\Jobs\ProcesarEnvioDte;
use App\Models\Documento;
use Illuminate\Console\Command;

class GenerarXmlDtePorId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:generar-xml-dte-por-id {id} {subir}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para (re) generar el xml de un DTE por su id, adicionalmente permite subir el archivo al SII';

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
        /* @var Documento $documento */
        $id = $this->argument('id');
        $subir = $this->argument('subir');

        $documento = Documento::find($id);

        if($documento){
            $xml_string = $documento->generarDTE();
            $xml = $documento->subirXmlDteS3($xml_string);
            $documento->archivos()->attach($xml->id, ['tipo' => TipoArchivo::DTE]);

            if($subir == 1){
                ProcesarEnvioDte::dispatch($documento->id);
            }
        }
    }
}
