<?php

namespace App\Console\Commands;

use App\Jobs\ProcesarEnvioDte;
use App\Models\Documento;
use Illuminate\Console\Command;

class ReenviarDte extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:reenviar-dte {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Commando para reenviar un DTE al SII';

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
        $documento = Documento::find($id);

        if($documento->glosaEstadoSii !== 'DTE Recibido'){
            ProcesarEnvioDte::dispatch($documento->id);
        }else{
            $this->info('El DTE no sera enviado, ya tiene estado "DTE Recibido"');
        }

    }
}
