<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Models\RespuestaDteXml;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerarXmlRespuestaRecepcion implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($respuestaDteId)
    {
        $this->id = $respuestaDteId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        /* @var RespuestaDteXml $respuesta */
        $respuesta = RespuestaDteXml::find($this->id);

        if (! empty($respuesta)) {
            $respuesta->generarXmlRecepcionDte();
            $xml = $respuesta->firmarRespuesta();
            $file = $respuesta->subirXmlRspuestaRecepcionS3($xml);
            $email_id = $respuesta->crearEmailRecepcionEnvio();
            EnviarXmlRespuestaRecepcionEnvio::dispatch($email_id);
        }
    }
}
