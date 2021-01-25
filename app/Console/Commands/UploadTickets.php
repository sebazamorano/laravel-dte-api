<?php

namespace App\Console\Commands;

use App\Jobs\ProcesarEnvioDteMultiple;
use App\Models\Documento;
use Illuminate\Console\Command;

class UploadTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:upload-tickets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Este comando se encarga de subir las ultimas 150 boletas de cada empresa';

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
        $ids = Documento::where(function($query){
            $query->where('tipo_documento_id', 20)
                ->orWhere('tipo_documento_id',  21);
        })
            ->where('fechaEmision', '>', '2020-12-31')
            ->where('empresa_id', 7)
            ->where('IO', 0)
            ->where(function($query){
                $query->where('glosaEstadoSii', '<>', 'DTE Recibido')->orWhereNull('glosaEstadoSii');
            })
            ->limit(150)
            ->pluck('id');

        ProcesarEnvioDteMultiple::dispatch($ids);
    }
}
