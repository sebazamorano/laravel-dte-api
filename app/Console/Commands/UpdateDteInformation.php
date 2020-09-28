<?php

namespace App\Console\Commands;

use App\Jobs\UpdateDteInformationWithRcv;
use App\Models\Empresa;
use Illuminate\Console\Command;

class UpdateDteInformation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:update-dte-information';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update the dte information from sii-rcv';

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
            $this->info('Enviando Job a la queue UpdateDteInformationWithRcv empresa [' . $company->rut . ']' . $company->razonSocial);
            UpdateDteInformationWithRcv::dispatch($company);
            echo "\n";
            $bar->advance();
        }
    }
}
