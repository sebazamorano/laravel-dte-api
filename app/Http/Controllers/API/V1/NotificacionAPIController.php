<?php

namespace App\Http\Controllers\API\V1;

use App\File;
use Aws\Sns\Message;
use App\Models\Email;
use App\Models\Empresa;
use Aws\Sns\MessageValidator;
use ZBateson\MailMimeParser\Message as MailMessage;
use App\Models\EmailDestinatario;
use App\Jobs\ProcesarCorreoEmpresa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AppBaseController;
use Aws\Sns\Exception\InvalidSnsMessageException;

class NotificacionAPIController extends AppBaseController
{
    public function correoRecepcionado()
    {
        /* @var Empresa $empresa */

        // Make sure the request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Log::error('no es post');
            http_response_code(405);
            die;
        }

        $message = Message::fromRawPostData();
        $validator = new MessageValidator();

        try {
            $validator->validate($message);
        } catch (InvalidSnsMessageException $e) {
            // Pretend we're not here if the message is invalid.
            http_response_code(404);
            Log::error('SNS Message Validation Error: '.$e->getMessage());
            die();
        }

        if (in_array($message['Type'], ['SubscriptionConfirmation', 'UnsubscribeConfirmation'])) {
            file_get_contents($message['SubscribeURL']);
        }

        //Log::error($message['Message'] ."\r\n" . $message['SubscribeURL']);
        //die();

        if ($message['Type'] == 'Notification') {
            $contenido_sns = json_decode($message['Message']);
            $contenido = Storage::cloud()->get($contenido_sns->receipt->action->objectKey);

            $lines = explode(PHP_EOL, $contenido);

            $borrar = [' for ', ';', PHP_EOL];
            $deliveredTo = trim(str_replace($borrar, '', $lines[3]));

            $mail_message = MailMessage::from($contenido);
            $date_object = $mail_message->getHeader('Date')->getDatetime();
            $modified = $date_object->setTimezone(new \DateTimeZone("America/Santiago"));
            $to = $mail_message->getHeader('To');
            $from = $mail_message->getHeader('From')->getAddresses()[0];
            $subject = $mail_message->getHeaderValue('Subject');
            $text = $mail_message->getTextContent();
            $html = $mail_message->getHtmlContent();

            $email = new Email();
            $email->addressFrom = $from->getEmail();
            $email->displayFrom = $from->getName();
            $email->texto = $text;
            $email->html = $html;
            $email->subject = $subject;
            $email->IO = 1;
            $email->fecha = $modified->format('Y-m-d H:i:s');
            $email->deliveredTo = $deliveredTo;
            $email->leido = 0;
            $email->procesado = 0;
            $email->resaltado = 0;
            $email->bandeja = 1;

            $empresa_id = 0;
            $empresa = Empresa::where('contactoSii', $deliveredTo)->orWhere('contactoEmpresas', $deliveredTo)->first();

            if (! empty($empresa)) {
                $email->empresa_id = $empresa->id;
                $empresa_id = $empresa->id;
            }

            /*$usuario = User::where('secondMail', $deliveredTo)->first();

            if(!empty($usuario))
            {
                // $email->
            }*/

            $email->save();

            foreach ($to->getAddresses() as $toAddress) {
                $destinatario = new EmailDestinatario();
                $destinatario->displayTo = $toAddress->getName();
                $destinatario->addressTo = $toAddress->getEmail();
                $destinatario->type = 1;
                $email->destinatarios()->save($destinatario);
            }

            $attachments = $mail_message->getAllAttachmentParts();

            if (count($attachments) > 0) {
                foreach ($attachments as $attachment) {
                    $contenido = $attachment->getContent();
                    $filename = $attachment->getFilename();
                    $type = $attachment->getContentType();
                    $file = File::uploadEmailAttachment($contenido, $filename, $type, 0, $empresa_id);
                    $email->adjuntos()->attach($file->id);
                }
            }

            if ($empresa_id != 0) {
                ProcesarCorreoEmpresa::dispatch($email->id);
            }
        }
    }
}
