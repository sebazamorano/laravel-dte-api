<?php

namespace App\Mail;

use App\Models\Documento;
use App\Models\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $email_id;
    protected $documento;

    /**
     * Create a new message instance.
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
            $msg = $this->markdown('emails.sendEmail')
                ->from($email->addressFrom)
                ->to($destinatario->addressTo)
                ->subject($email->subject)
                ->attachData($this->documento->obtenerPdfString(), $this->documento->generarDteId().'.pdf', ['mime' => 'application/pdf'])
                ->with('email', $email);


            if($adjunto){
                $msg->attachFromStorageDisk(config('filesystems.cloud'), $adjunto->file_path, $adjunto->file_name, ['mime'=> $adjunto->mime_type]);
            }
        }
    }
}
