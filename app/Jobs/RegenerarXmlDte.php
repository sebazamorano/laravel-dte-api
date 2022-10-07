<?php

namespace App\Jobs;

use App\Components\TipoArchivo;
use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RegenerarXmlDte implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public  $id;

    /**
     * Create a new job instance.
     *
     * @return void
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
        $documento = Documento::find($this->id);

        if($documento){
            $xml_string = $documento->generarDTE();
            $xml = $documento->subirXmlDteS3($xml_string);
            $documento->archivos()->attach($xml->id, ['tipo' => TipoArchivo::DTE]);
            $documento->glosaEstadoSii = 'DTE No Recibido';
            $documento->save();
        }

        return;
    }
}
