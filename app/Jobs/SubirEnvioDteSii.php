<?php

namespace App\Jobs;

use App\Components\Sii;
use App\Models\EnvioDte;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubirEnvioDteSii implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $envio_dte_id;

    /**
     * Create a new job instance.
     *
     * @param $envio_id
     */
    public function __construct($envio_id)
    {
        $this->envio_dte_id = $envio_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* @var EnvioDte $envio */
        $envio = EnvioDte::find($this->envio_dte_id);

        if($envio !== null){
            $envio->subirAllSii();
        }
    }
}
