<?php

namespace App\Console\Commands;

use App\Jobs\UpdateDteInformationWithWs;
use App\Models\Documento;
use App\Models\Empresa;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateDteInformationWs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:update-dte-information-ws';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update the dte information from sii-ws';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $companies = Empresa::all();

        $bar = $this->output->createProgressBar(count($companies));
        $bar->start();
        echo "\n";

        foreach ($companies as $company) {
            $this->info('Obteniendo documentos de la empresa [' . $company->rut . ']' . $company->razonSocial);
            $documentos = Documento::getDocumentsCreatedInLastFiveMins($company->id);
            foreach($documentos as $documento){
                /* @var Documento $documento */

                if(($documento->idDoc->TipoDTE == "41" || $documento->idDoc->TipoDTE == "39") && Carbon::now()->gte('2021-01-01 00:00:00') ){
                    $this->info('Enviando Job a la queue UpdateDteInformationWithWs del documento boleta id' . $documento->id);
                    UpdateDteInformationWithWs::dispatch($documento);
                }

                if($documento->idDoc->TipoDTE !== "41" && $documento->idDoc->TipoDTE !== "39"){
                    $this->info('Enviando Job a la queue UpdateDteInformationWithWs del documento id' . $documento->id);
                    UpdateDteInformationWithWs::dispatch($documento);
                }

            }
            echo "\n";
            $bar->advance();
        }
    }
}
