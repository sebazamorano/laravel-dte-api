<?php

namespace App\Jobs;

use App\Components\Sii;
use App\Models\EnvioDte;
use App\Models\Documento;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProcesarEnvioDte implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $id;

    /**
     * Create a new job instance.
     * @param integer $documento_id
     * @return void
     */
    public function __construct($documento_id)
    {
        $this->id = $documento_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* @var Documento $documento */
        /* @var EnvioDte $empaquetado */
        try {
            $documento = Documento::find($this->id);
            $empaque = [];
            array_push($empaque, $documento);
            $boleta = in_array($documento->idDoc->TipoDTE, [39, 41]) ? 1 : 0;
            $empaquetado = EnvioDte::empaquetarDtes($empaque, 0, $boleta);

            if (! $empaquetado) {
            }

            $xml_string = $empaquetado->generarXML();
            $file = $empaquetado->subirXmlS3($xml_string);

            if (! $file) {
            }

            $empaquetado->archivos()->attach($file->id);

            if($boleta !== 1){
                $siiComponent = new Sii($documento->empresa);
                $data = $siiComponent->subirEnvioDteAlSii($empaquetado, $file->id);
                $empaquetado->estado = $data['status'];
                $empaquetado->rspUpload = Sii::getRspUploadTextFromStatus($empaquetado->estado);

                if ($data['status'] == 0) {
                    $empaquetado->trackid = $data['trackId'];
                }

                if ($data['status'] == 99) {
                    $empaquetado->rspUpload = $data['error'];
                }

                $empaquetado->update();
            }

        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }
}
