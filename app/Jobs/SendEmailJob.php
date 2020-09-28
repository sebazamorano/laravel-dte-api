<?php

namespace App\Jobs;

use App\Models\Documento;
use Carbon\Carbon;
use App\Models\Email;
use App\Mail\SendEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email_id;
    protected $documento;

    /**
     * Create a new job instance.
     *
     * @param $id
     * @param Documento $documento
     */
    public function __construct($id, Documento $documento)
    {
        $this->email_id = $id;
        $this->documento = $documento;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new SendEmail($this->email_id, $this->documento);
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
