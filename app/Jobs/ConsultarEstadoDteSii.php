<?php

namespace App\Jobs;

use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ConsultarEstadoDteSii implements ShouldQueue
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
        /* @var Documento $documento */
        echo "Consultando Estado DTE " . $this->id . "\n";
        $documento = Documento::find($this->id);
        $documento->consultarEstadoSii();
        echo "Consulta Estado DTE " . $this->id . " Terminada \n";
    }
}
