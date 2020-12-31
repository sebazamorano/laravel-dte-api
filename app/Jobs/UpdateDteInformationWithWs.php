<?php

namespace App\Jobs;

use App\Components\Sii;
use App\Models\CertificadoEmpresa;
use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateDteInformationWithWs implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $documento;

    /**
     * Create a new job instance.
     *
     * @param Documento $documento
     */
    public function __construct(Documento $documento)
    {
        $this->documento = $documento;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $this->documento->consultarEstadoSii();
    }
}
