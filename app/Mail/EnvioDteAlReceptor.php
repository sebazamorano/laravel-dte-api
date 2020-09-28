<?php

namespace App\Mail;

use App\File;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\EmailDestinatario;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnvioDteAlReceptor extends Mailable
{
    use Queueable, SerializesModels;

    protected $email_id;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->email_id = $id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /* @var Email $email */
        /* @var File $adjunto */
        /* @var EmailDestinatario $destinatario */
        $email = Email::find($this->email_id);

        $adjunto = $email->adjuntos()->first();

        $destinatario = $email->destinatarios()->where('type', 1)->first();

        if (! empty($email)) {
            return $this->markdown('emails.enviosdtes.enviar')
                ->from($email->addressFrom)
                ->to($destinatario->addressTo)
                ->subject($email->subject)
                ->attachFromStorageDisk('s3', $adjunto->file_path, $adjunto->file_name, ['mime'=> $adjunto->mime_type])
                ->with('email', $email);
        }
    }
}
