<?php

namespace App\Console\Commands;

use App\Models\SII\ConsumoFolios\FolioConsumption;
use Illuminate\Console\Command;

class GenerarXmlRcfById extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sii:generar-xml-rcf-by-id {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to create xml of an rcf by its id';

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
        /* @var FolioConsumption $folio */
        $id = $this->argument('id');

        $folio = FolioConsumption::find($id);
        $xml = $folio->generarXml();
        $signed_xml = $folio->firmarXml($xml);
        $file = $folio->subirXml($signed_xml);
        $folio->uploadSii();
    }
}
