<?php

namespace App\Jobs;

use App\Components\Xml;
use App\Models\SII\ConsumoFolios\FolioConsumption;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GuardarResultadoConsumoFolios implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $xml_string;

    /**
     * Create a new job instance.
     *
     * @param $xml
     */
    public function __construct($xml)
    {
        $this->xml_string = $xml;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $xml = new \DOMDocument();
        $xml->loadXML($this->xml_string);
        $consumoFolios = Xml::parseResultadoConsumoFolios($xml);
        FolioConsumption::saveResponse($consumoFolios);
    }
}
