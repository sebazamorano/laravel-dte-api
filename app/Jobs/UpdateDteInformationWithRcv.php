<?php

namespace App\Jobs;

use App\Components\Sii;
use App\Models\Empresa;
use App\Models\SII\DocumentInformation;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdateDteInformationWithRcv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $empresa;
    protected $siiComponent;
    /**
     * Create a new job instance.
     *
     * @param Empresa $empresa
     */
    public function __construct(Empresa $empresa)
    {
        $this->empresa = $empresa;
        $this->siiComponent = new Sii($this->empresa);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [
            'operacion' => 'VENTA',
            'estadoContab' => 'REGISTRO',
            'ptributario' => Carbon::now()->format('Ym'),
            'tipo' => 'DETALLE',
        ];

        $rcv = $this->siiComponent->getRCVDetail($data);
        $decoded = json_decode($rcv);
        $fp = fopen('php://filter/read=convert.base64-encode/resource=php://temp/maxmemory:1048576', 'w');

        if ($fp === false) {
            echo 'Failed to open temporary file';
            exit();
        }

        if (count($decoded->data) > 1) {
            $count = 0;
            foreach ($decoded->data as $line) {
                if ($count > 0) {
                    $line_to_read = explode(';', $line);
                    $data_readed = [
                        'company_id' => $this->empresa->id,
                        'rut' => $this->empresa->rut,
                        'doc_type' => $line_to_read[1],
                        'folio' => $line_to_read[5],
                        'reception_date' => Carbon::createFromFormat('d/m/Y H:i:s', $line_to_read[7])->format('Y-m-d H:i:s'),
                    ];
                    $di = DocumentInformation::firstOrCreate($data_readed);
                    Log::info('Fecha Recepción: '.$di->reception_date."\n");
                }
                $count++;
            }

            rewind($fp);
            fclose($fp);
        }
    }
}
