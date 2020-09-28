<?php

namespace App\Console\Commands;

use App\Jobs\GenerarXmlRcf;
use App\Models\Empresa;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;


class GenerateMissingFirstMonthRcf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:generate-missing-first-month-rcf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permite generar todos los reporte de consumos de folios faltantes del mes-anio de creación de la empresa';

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
        /* @var $empresa Empresa */
        $empresas = Empresa::all();

        $bar = $this->output->createProgressBar(count($empresas));

        foreach($empresas as $empresa){
            if($empresa->documentoEstaAutorizado(39) || $empresa->documentoEstaAutorizado(41)){
                $creation_date = Carbon::parse($empresa->created_at);
                $startOfMonth = $creation_date->startOfMonth()->format('Y-m-d');
                $endOfMonth = $creation_date->endOfMonth()->addDays(1)->format('Y-m-d');
                $periods = CarbonPeriod::create($startOfMonth, '1 day', $endOfMonth);
                $yesterday = Carbon::now();
                foreach($periods as $period){
                    if($period->format('Y-m-d') < $yesterday->format('Y-m-d')){
                        $this->info("Enviando Job a la queue GenerarXmlRcf empresa [{$empresa->rut}] {$empresa->razonSocial} - {$period->toDateString()}");
                        GenerarXmlRcf::dispatch($empresa, $period->toDateString());
                    }
                }
            }
            $bar->advance();
        }
    }
}
