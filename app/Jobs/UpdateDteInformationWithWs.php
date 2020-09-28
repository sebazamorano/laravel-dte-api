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
    private $siiComponent;

    /**
     * Create a new job instance.
     *
     * @param Documento $documento
     */
    public function __construct(Documento $documento)
    {
        $this->documento = $documento;
        $this->siiComponent = new Sii($this->documento->empresa);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* @var CertificadoEmpresa $certificado  */
        $certificado = $this->documento->empresa->certificados()->where('enUso', 1)->first();
        $documento = [
            'rut_emisor' => $this->documento->emisor->RUTEmisor,
            'rut_receptor' => $this->documento->receptor->RUTRecep,
            'rut_consultante' => $certificado->rut,
            'tipo' => $this->documento->idDoc->TipoDTE,
            'folio' => (string) $this->documento->idDoc->Folio,
            'fecha_emision' => $this->documento->idDoc->FchEmis->format('dmY'),
            'monto' => (string) $this->documento->totales->MntTotal
        ];

        $data = $this->siiComponent->consultarEstadoDte($documento);
        $formato = str_replace('SII:', '', $data );
        $xml = simplexml_load_string($formato);
        $this->documento->estadoSii = $xml->RESP_HDR->ESTADO;
        $this->documento->glosaEstadoSii = $xml->RESP_HDR->GLOSA_ESTADO;
        $this->documento->errCode = $xml->RESP_HDR->ERR_CODE;
        $this->documento->glosaErrSii = $xml->RESP_HDR->GLOSA_ERR;
        $this->documento->save();
        Log::info('Documento con ID: ' . $this->documento->id . ' actualizado con estado:' . $this->documento->glosaEstadoSii);
    }
}
