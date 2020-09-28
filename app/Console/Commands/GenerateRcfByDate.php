<?php

namespace App\Console\Commands;

use App\Components\Sii;
use App\Jobs\GenerarXmlRcf;
use App\Models\Empresa;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateRcfByDate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:generate-rcf-by-date {date} {company_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate xml of rcf by date';

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
        /* @var Empresa $company */
        $date = $this->argument('date');

        $company_id = $this->argument('company_id');

        if(isset($company_id)){
            $company = Empresa::find($company_id);
            if($company->documentoEstaAutorizado(39) || $company->documentoEstaAutorizado(41)){
                $this->info('Enviando Job a la queue GenerarXmlRcf empresa [' . $company->rut . ']' . $company->razonSocial);
                GenerarXmlRcf::dispatch($company, $date);
            }
        }else{
            $environment = config('dte.environment');

            $companies = Empresa::all();
            $bar = $this->output->createProgressBar(count($companies));
            $bar->start();

            foreach ($companies as $company) {
                if($company->documentoEstaAutorizado(39) || $company->documentoEstaAutorizado(41)){
                    $this->info('Enviando Job a la queue GenerarXmlRcf empresa [' . $company->rut . ']' . $company->razonSocial);
                    GenerarXmlRcf::dispatch($company, $date);
                }
                $bar->advance();
            }
        }


    }
}
