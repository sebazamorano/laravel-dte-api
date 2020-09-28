<?php

namespace App\Jobs;

use App\Mail\Information;
use App\Mail\RcfNotGenerated;
use App\Models\SII\ConsumoFolios\FolioConsumption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class GenerarXmlRcf implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $company;
    protected $date;

    /**
     * Create a new job instance.
     *
     * @param $company
     * @param $date
     */
    public function __construct($company, $date)
    {
        $this->company = $company;
        $this->date = $date;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* */
        $folio = FolioConsumption::createInfo($this->company->id, $this->date);

        if($folio !== false){
            $xml = $folio->generarXml();
            $signed_xml = $folio->firmarXml($xml);
            $file = $folio->subirXml($signed_xml);
            $folio->uploadSii($file);

            $details = [
                'title' => 'Reporte consumo folios generado exitosamente',
                'message' => '[' . $this->date . '] Reporte consumo generado con exito para la empresa [' . $this->company->rut . '] ' . $this->company->razon_social,
            ];
            Mail::to(config('dte.cron_mail'))->send(new Information($details));

        }else{
            $details = [
                'message' => 'Reporte consumo generado con anterioridad para la empresa',
                'company' => $this->company->razonSocial,
                'date' => $this->date
            ];
            Mail::to(config('dte.cron_mail'))->send(new RcfNotGenerated($details));
            echo $details['message'] . ' ' . $details['company'] . ' en la fecha: ' . $details['date'];
        }
    }
}
