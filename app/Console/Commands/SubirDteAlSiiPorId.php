<?php

namespace App\Console\Commands;

use App\Jobs\ProcesarEnvioDte;
use App\Models\Documento;
use Illuminate\Console\Command;

class SubirDteAlSiiPorId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:subir-xml-dte-por-id {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Comando para (re) subir XML de un DTE al SII. [Crea envio nuevo]';

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
        /* @var Documento */
        $id = $this->argument('id');

        $documento = Documento::find($id);

        if($documento){
            ProcesarEnvioDte::dispatch($id);
        }

    }
}
