<?php

namespace App\Console\Commands;

use App\Models\SII\ConsumoFolios\FolioConsumption;
use Illuminate\Console\Command;

class SubirRcfAlSiiById extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:subir-rcf-by-id {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to update the rcf to sii by rcf id';

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
        /* @var FolioConsumption $rcf */
        $id = $this->argument('id');

        $rcf = FolioConsumption::find($id);
        $rcf->uploadSii();
    }
}
