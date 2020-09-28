<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Components\Sii;
use App\Models\Empresa;
use App\Models\Documento;
use Illuminate\Console\Command;
use App\Models\SII\AcceptanceClaim;
use App\Models\SII\DocumentInformation;

class UpdateDteHistory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:update-dte-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update the dte history from sii';

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
        $companys = Empresa::all();

        $bar = $this->output->createProgressBar(count($companys));
        $bar->start();
        echo "\n";

        foreach ($companys as $company) {
            $documentos = Documento::getDocumentsToUpdateSiiHistory($company->id);
            $bar2 = $this->output->createProgressBar(count($documentos));
            /* @var Documento $documento */
            $this->info("Cargando empresa: $company->razonSocial");
            $bar2->start();
            $bar->advance();

            try {
                $this->info("\nActualizando Documentos.");
                foreach ($documentos as $documento) {
                    $bar2->advance();
                    //echo "\n";
                    $data = [
                        'company_id' => $documento->empresa_id,
                        'doc_type' => $documento->idDoc->TipoDTE,
                        'rut' => $documento->emisor->RUTEmisor,
                        'folio' => $documento->idDoc->Folio,
                    ];

                    $siiComponent = new Sii($documento->empresa);
                    $consultas_ws = DocumentInformation::getModel($data);
                    $consultas_listado = $siiComponent->listHistoryDocEvents($data);

                    if (isset($consultas_listado->listaEventosDoc)) {
                        foreach ($consultas_listado->listaEventosDoc as $listado) {
                            if (isset($consultas_listado->listaEventosDoc->codEvento)) {
                                $accionDoc = $consultas_listado->listaEventosDoc->codEvento;
                                $descResp = utf8_encode($consultas_listado->listaEventosDoc->descEvento);
                                $date = Carbon::createFromFormat('d-m-Y H:i:s', $consultas_listado->listaEventosDoc->fechaEvento);
                            } else {
                                $accionDoc = $listado->codEvento;
                                $descResp = utf8_encode($listado->descEvento);
                                $date = Carbon::createFromFormat('d-m-Y H:i:s', $listado->fechaEvento);
                            }

                            $data_unique = $data;
                            $data_unique['action'] = $accionDoc;
                            $data_unique['response_description'] = $descResp;
                            $data_unique['event_date'] = $date->format('Y-m-d H:i:s');

                            $acceptance_claim = new AcceptanceClaim();
                            if ($acceptance_claim->isUnique($data_unique)) {
                                $acceptance_claim = AcceptanceClaim::create($data);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("\nProceso fallido.");
                echo $e->getMessage()."\n";
            }
        }
        $this->info("\nProceso terminado.");
    }
}
