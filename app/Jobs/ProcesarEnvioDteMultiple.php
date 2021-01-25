<?php

namespace App\Jobs;

use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcesarEnvioDteMultiple implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $ids;

    /**
     * Create a new job instance.
     *
     * @param $ids
     */
    public function __construct($ids)
    {
        $this->ids = $ids;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $documentos = Documento::find($this->ids);

        $different_documents_count = 0;
        $boleta = 0;

        if($documentos->contains('tipo_documento_id', 20) || $documentos->contains('tipo_documento_id', 21)){
            $different_documents_count++;
            $boleta = 1;
        }

        if($documentos->contains('tipo_documento_id', 1) || $documentos->contains('tipo_documento_id', 2)
            || $documentos->contains('tipo_documento_id', 4) || $documentos->contains('tipo_documento_id', 5)
            || $documentos->contains('tipo_documento_id', 6) || $documentos->contains('tipo_documento_id', 7)){
            $different_documents_count++;
        }


        if($different_documents_count == 1){
            $envio = \App\Models\EnvioDte::empaquetarDtes($documentos, 0, $boleta);
            $xml_string = $envio->generarXML();
            $file = $envio->subirXmlS3($xml_string);
            $envio->archivos()->attach($file->id);
            $envio->subirAllSii();
        }
    }
}
