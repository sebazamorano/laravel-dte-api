<?php


namespace App\Components;


use App\Jobs\GuardarResultadoConsumoFolios;
use App\Jobs\ProcesarResultadoEnvio;
use App\Models\SII\ConsumoFolios\FolioConsumption;

class ProcessEmail
{

    public static function readEmail(){
        $host = config('dte.mail_reception_host');
        $username = config('dte.mail_reception_username');
        $password = config('dte.mail_reception_password');
        $port = config('dte.mail_reception_port');
        $mailbox = "{{$host}:{$port}/imap/ssl}INBOX";

        if(empty($host)){
            return true;
        }

        $reader = new \App\Components\MailReader($mailbox, $username, $password);
        $connection = $reader->getConnection();
        $result = $reader->returnUnseen();
        $valid = true;

        if(is_array($result)){
            $EnvioDTE = 0;
            $emails_quantity = 0;
            foreach($result as $message){
                $structure = imap_fetchstructure($connection, $message);
                imap_fetchbody($connection, $message, 1);
                $headers = imap_headerinfo($connection, $message);
                $reply_to = $headers->reply_to[0]->mailbox . "@" . $headers->reply_to[0]->host;

                $attachments = [];

                if(isset($structure->parts) && count($structure->parts)) {

                    for($i = 0; $i < count($structure->parts); $i++) {

                        $attachments[$i] = array(
                            'is_attachment' => false,
                            'filename' => '',
                            'name' => '',
                            'attachment' => ''
                        );

                        if($structure->parts[$i]->ifdparameters) {
                            foreach($structure->parts[$i]->dparameters as $object) {
                                if(strtolower($object->attribute) == 'filename') {
                                    $attachments[$i]['is_attachment'] = true;
                                    $attachments[$i]['filename'] = $object->value;
                                }
                            }
                        }

                        if($structure->parts[$i]->ifparameters) {
                            foreach($structure->parts[$i]->parameters as $object) {
                                if(strtolower($object->attribute) == 'name') {
                                    $attachments[$i]['is_attachment'] = true;
                                    $attachments[$i]['name'] = $object->value;
                                }
                            }
                        }

                        if($attachments[$i]['is_attachment']) {
                            $attachments[$i]['attachment'] = imap_fetchbody($connection, $message, $i+1);
                            if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                            }
                            elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                            }
                        }
                    }
                }

                foreach($attachments as $attachment){
                    $xml = null;
                    $isXML = false;
                    if($attachment['is_attachment'] == true){
                        libxml_use_internal_errors(true);
                        $xml = new \DOMDocument('1.0');
                        $xml->preserveWhiteSpace = true;
                        $xmlToVerify = new \DOMDocument('1.0');
                        $xmlToVerify->preserveWhiteSpace = true;
                        $xmlToVerify->formatOutput = false;

                        if(!$xml->loadXML($attachment['attachment'])){
                            $errors = libxml_get_errors();
                            $isXML =   false;
                        }else{
                            $isXML = true;
                            $xml->loadXML($attachment['attachment']);
                            $xml->encoding = "ISO-8859-1";
                            $tagName =  $xml->documentElement->tagName;
                            $xmlToVerify->loadXML($attachment['attachment']);
                            $xmlToVerify->encoding = "ISO-8859-1";
                        }
                    }

                    if($isXML){
                        switch ($tagName){
                            case 'RESULTADO_ENVIO':
                                ProcesarResultadoEnvio::dispatch(utf8_encode($xml->saveXML()));
                                break;
                            case 'EnvioDTE':
                                //exit();
                                break;
                            case 'ResultadoConsumoFolios':
                                GuardarResultadoConsumoFolios::dispatch(utf8_encode($xml->saveXML()));
                                break;
                            default:
                                break;
                        }
                    }
                }

                $emails_quantity++;
            }
        }
        return true;
    }
}
