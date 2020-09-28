<?php

namespace App\Console\Commands;

use App\Components\Sii;
use App\Jobs\GenerarXmlRcf;
use App\Models\Empresa;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateRcf extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:generate-rcf';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to generate xml of rcf';

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
        $date = Carbon::yesterday()->format('Y-m-d');

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
