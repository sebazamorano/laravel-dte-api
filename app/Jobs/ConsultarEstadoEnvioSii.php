<?php

namespace App\Jobs;

use App\Models\EnvioDte;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConsultarEstadoEnvioSii implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $id;

    /**
     * Create a new job instance.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* @var EnvioDte $envio */
        echo "Consultando Estado Envio " . $this->id . "\n";
        $envio = EnvioDte::find($this->id);
        $envio->consultarEstadoSii();
        echo "Consulta Estado Envio " . $this->id . " Terminada \n";
    }
}
