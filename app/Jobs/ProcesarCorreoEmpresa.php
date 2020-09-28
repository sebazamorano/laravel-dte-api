<?php

namespace App\Jobs;

use App\File;
use App\Models\Email;
use App\Components\Xml;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcesarCorreoEmpresa implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email_id)
    {
        $this->id = $email_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* @var Email $email */
        /* @var File $adjunto */
        $email = Email::find($this->id);

        if (empty($email)) {
            Log::error('No se encontro en la base de datos el email con id '.$this->id);
            $this->delete();
            exit();
        }

        if (count($email->adjuntos) > 0) {
            foreach ($email->adjuntos as $adjunto) {
                if (in_array($adjunto->mime_type, ['application/xml', 'text/xml', 'application/octet-stream'])) {
                    $xml = new \DOMDocument('1.0');

                    if ($xml->loadXML($adjunto->content())) {
                        $tagName = $xml->documentElement->tagName;

                        switch ($tagName) {
                            case 'RESULTADO_ENVIO':
                                Log::info('El email con id '.$email->id.' entro al proceso [resultado_envio]');
                                Xml::procesarResultadoEnvio($email, $xml);

                                break;

                            case 'EnvioDTE':
                                Log::info('El email con id '.$email->id.' entro al proceso [envio_dte]');
                                Xml::procesarEnvioDte($email, $xml, $adjunto);

                                break;

                            case 'ResultadoConsumoFolios':
                                Xml::parseResultadoConsumoFolios($xml);

                                break;
                        }
                    }
                }
            }
        }

        $email->procesado = 1;
        $email->update();
    }
}
