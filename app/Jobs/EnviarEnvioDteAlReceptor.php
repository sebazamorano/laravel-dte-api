<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use App\Mail\EnvioDteAlReceptor;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class EnviarEnvioDteAlReceptor implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->email_id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /* @var Email $email_update */
        $email = new EnvioDteAlReceptor($this->email_id);
        $email_update = Email::find($this->email_id);
        $email_update->fecha = Carbon::now()->format('Y-m-d H:i:s');
        $email_update->update();

        $message_id = '';

        Mail::send($email, [], function ($message) use ($message_id, $email_update) {
            $message_id = $message->getId();
            Log::error('el mensaje id es '.$message_id);
            $email_update->cloud_id = $message_id;
            $email_update->update();
        });
    }
}
