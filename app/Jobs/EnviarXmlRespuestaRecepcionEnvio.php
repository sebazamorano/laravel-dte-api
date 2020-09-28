<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\RespuestaRecepcionEnvioAlEmisor;

class EnviarXmlRespuestaRecepcionEnvio implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email_id)
    {
        $this->email_id = $email_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new RespuestaRecepcionEnvioAlEmisor($this->email_id);
        $email_update = Email::find($this->email_id);
        $email_update->fecha = Carbon::now()->format('Y-m-d H:i:s');
        $email_update->update();

        $message_id = '';

        Mail::send($email, [], function ($message) use ($message_id) {
            $message_id = $message->getId();
        });

        Log::error('el mensaje id es '.$message_id);
    }
}
