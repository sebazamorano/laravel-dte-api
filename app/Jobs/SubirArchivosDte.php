<?php

namespace App\Jobs;

use App\Models\Documento;
use Illuminate\Bus\Queueable;
use App\Components\TipoArchivo;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SubirArchivosDte implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $pdf_string;

    protected $xml_string;

    protected $id;

    /**
     * Create a new job instance.
     * @param int $documento_id
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
        try {
            /* @var Documento $documento */
            $documento = Documento::find($this->id);

            $path_xml = 'archivos/'.$documento->id.'.xml';
            $path_pdf = 'archivos/'.$documento->id.'.pdf';
            $xml = Storage::get($path_xml);
            $pdf = Storage::get($path_pdf);

            Storage::delete([$path_xml, $path_pdf]);

            $xml = $documento->subirXmlDteS3($xml);
            $documento->archivos()->attach($xml->id, ['tipo' => TipoArchivo::DTE]);

            /*$pdf = $documento->subirPdfDteS3($pdf);
            $documento->archivos()->attach($pdf->id, ['tipo'=> TipoArchivo::PDF_DTE]);
             */

            ProcesarEnvioDte::dispatch($documento->id);
        } catch (\Exception $e) {
            Log::error('Proceso SubirArchivosDte del documento con ID '.$documento->id.' fallido');
        }
    }
}
