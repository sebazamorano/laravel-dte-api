<?php
ini_set('max_execution_time', 3600);

require_once(Constantes::systemPath . "protected/extensions/globals.php");
require_once(Constantes::systemPath . "protected/extensions/recepcion_correos/reader.php");
require_once(Constantes::systemPath . "protected/extensions/recepcion_correos/validaciones_recepcion.php");
require_once(Constantes::systemPath . "protected/extensions/xmlseclibs/XmlseclibsAdapter.php");
require_once(Constantes::systemPath . "protected/extensions/mailer/PHPMailerAutoload.php");

function leerCorreos($rutEmisor = ""){
    /* @var $model \App\Models\Empresa */

    $flash = "";
    try {
        /* @var $datos_email \App\Models\Empresa */

        $host = config('dte.mail_reception_host');
        $username = config('dte.mail_reception_username');
        $password = config('dte.mail_reception_password');
        $port = config('dte.mail_reception_port');
        $mailbox = "{{$host}:{$port}/imap/ssl/novalidate-cert}INBOX";

        $reader = new \App\Components\MailReader($mailbox, $username, $password);

        $mbox  = $reader->connection;

        $result = $reader->returnUnseen();
        $valid = true;

        if(is_array($result)){
            $EnvioDTE = 0;
            $cantidad_correos = 0;
            foreach($result as $msj){
                $cantidad_correos++;

                if($rutEmisor != ""){
                    echo "----- $cantidad_correos correos leidos ----- \n";
                }else{
                    echo "----- $cantidad_correos correos leidos ----- </br>";
                }


                $structure = imap_fetchstructure($mbox, $msj);
                imap_fetchbody($mbox, $msj, 1);
                $headers = imap_headerinfo($mbox, $msj);
                $reply_to = $headers->reply_to[0]->mailbox . "@" . $headers->reply_to[0]->host;
                $fromaddress = imap_utf8($headers->fromaddress);
                $body_unseen = imap_body($mbox, $msj);

                $attachments = array();
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
                            $attachments[$i]['attachment'] = imap_fetchbody($mbox, $msj, $i+1);
                            if($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                                $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                            }
                            elseif($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                                $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                            }
                        }
                    }
                }

                foreach($attachments as $adjunto){
                    if($adjunto['is_attachment'] == 1){
                        try{
                            $isXML = true;
                            libxml_use_internal_errors(true);
                            $xml = new DOMDocument('1.0');
                            $xml->preserveWhiteSpace = TRUE;
                            $xmlToVerify = new DOMDocument('1.0');
                            $xmlToVerify->preserveWhiteSpace = TRUE;
                            $xmlToVerify->formatOutput = FALSE;

                            if(!$xml->loadXML($adjunto['attachment'])){
                                $errors = libxml_get_errors();
                                $isXML =  $isXML && false;
                            }else{
                                $isXML = $isXML && true;
                                $xml->loadXML($adjunto['attachment']);
                                $xml->encoding = "ISO-8859-1";
                                $tagName =  $xml->documentElement->tagName;
                                $xmlToVerify->loadXML($adjunto['attachment']);
                                $xmlToVerify->encoding = "ISO-8859-1";
                            }

                        }catch(Exception $e){
                            echo $e->getMessage();
                            exit();
                        }

                        if($isXML){
                            switch($tagName){
                                case 'ResultadoEnvioLibro':

                                    $TrackId = $xml->getElementsByTagName("TrackId")->item(0)->nodeValue;
                                    $RutEmisorLib = $xml->getElementsByTagName("RutEmisor")->item(0)->nodeValue;
                                    $RutEnviaLib = $xml->getElementsByTagName("RutEnvia")->item(0)->nodeValue;
                                    $TmstRecepcion = $xml->getElementsByTagName("TmstRecepcion")->item(0)->nodeValue;
                                    $EstadoEnvio = $xml->getElementsByTagName("EstadoEnvio")->item(0)->nodeValue;
                                    $TipoSegmento = $xml->getElementsByTagName("TipoSegmento")->item(0)->nodeValue;
                                    $EstadoEnvio = $xml->getElementsByTagName("EstadoEnvio")->item(0)->nodeValue;

                                    if($datos_email->rut == $RutEmisorLib){

                                        if($xml->getElementsByTagName("TipoLibro")->length == 1){
                                            $TipoLibro = $xml->getElementsByTagName("TipoLibro")->item(0)->nodeValue;
                                        }else{
                                            $TipoLibro = "";
                                        }

                                        if($xml->getElementsByTagName("TipoOperacion")->length == 1){
                                            $TipoOperacion = $xml->getElementsByTagName("TipoOperacion")->item(0)->nodeValue;
                                        }else{
                                            $TipoOperacion = "";
                                        }

                                        if($xml->getElementsByTagName("PeriodoTributario")->length == 1){
                                            $PeriodoTributario = $xml->getElementsByTagName("PeriodoTributario")->item(0)->nodeValue;
                                        }else{
                                            $PeriodoTributario = "";
                                        }

                                        if($xml->getElementsByTagName("EstadoLibro")->length == 1){
                                            $EstadoLibro = $xml->getElementsByTagName("EstadoLibro")->item(0)->nodeValue;
                                        }else{
                                            $EstadoLibro = "";
                                        }

                                        $Libro = new Libros();
                                        $Libro = $Libro->model()->findByAttributes(array('idemisor'=>$datos_email->idemisor, 'trackid'=>$TrackId));

                                        TrackingSystem::generarLog($Libro->idlibro, 10, $EstadoEnvio, 1);
                                        TrackingSystem::generarLog($Libro->idlibro, 10, $EstadoLibro, 1);

                                        if($xml->getElementsByTagName("ErrorEnvioLibro")->length == 1){

                                            $DetErrEnvio_nodes = $xml->getElementsByTagName("DetErrEnvio");
                                            foreach($DetErrEnvio_nodes as $DetErrEnvio_node){
                                                $erroresLibros = new Erroreslibros();
                                                $erroresLibros->idlibro = $Libro->idlibro;
                                                $erroresLibros->deterrenvio = $DetErrEnvio_node->nodeValue;
                                                $erroresLibros->save();
                                            }

                                        }
                                    }
                                    break;

                                case 'EnvioRecibos':
                                    $mensaje = "";
                                    $RutRecibe = $xml->getElementsByTagName("RutRecibe")->item(0)->nodeValue;
                                    $RutResponde = $xml->getElementsByTagName("RutResponde")->item(0)->nodeValue;

                                    if($xml->getElementsByTagName("NmbContacto")->length == 1){
                                        $NmbContacto = $xml->getElementsByTagName("NmbContacto")->item(0)->nodeValue;
                                    }else{
                                        $NmbContacto = NULL;
                                    }

                                    if($xml->getElementsByTagName("FonoContacto")->length == 1){
                                        $FonoContacto = $xml->getElementsByTagName("FonoContacto")->item(0)->nodeValue;
                                    }else{
                                        $FonoContacto = NULL;
                                    }

                                    if($xml->getElementsByTagName("MailContacto")->length == 1){
                                        $MailContacto = $xml->getElementsByTagName("MailContacto")->item(0)->nodeValue;
                                    }else{
                                        $MailContacto = NULL;
                                    }

                                    if($xml->getElementsByTagName("TmstFirmaEnv")->length == 1){
                                        $TmstFirmaResp = $xml->getElementsByTagName("TmstFirmaEnv")->item(0)->nodeValue;
                                    }else{
                                        $TmstFirmaResp = "";
                                    }

                                    if($RutRecibe == $datos_email->rut){
                                        $valid = true;
                                        $caratula_respuesta = new CaratulasRespuestasRecibidas();
                                        $caratula_respuesta->idemisor = $datos_email->idemisor;
                                        $caratula_respuesta->RutResponde = $RutResponde;
                                        $caratula_respuesta->RutRecibe = $RutRecibe;
                                        $caratula_respuesta->NmbContacto = $NmbContacto;
                                        $caratula_respuesta->FonoContacto = $FonoContacto;
                                        $caratula_respuesta->MailContacto = $MailContacto;
                                        $caratula_respuesta->TmstFirma = $TmstFirmaResp;
                                        $caratula_respuesta->NombreArchivo = $adjunto['filename'];

                                        if($caratula_respuesta->NombreArchivo == ""){
                                            $caratula_respuesta->NombreArchivo = ".xml";
                                        }
                                        //$caratula_respuesta->Archivo = base64_encode($adjunto['attachment']);
                                        $caratula_respuesta->create_date = date('Y-m-d H:i:s');
                                        $pRutEmpresa= substr($datos_email->rut,0, -2);

                                        if($caratula_respuesta->save()){
                                            $valid = $valid && true;
                                        }else{
                                            $valid = $valid && false;
                                            foreach ($caratula_respuesta->getErrors() as $attribute => $error)
                                            {
                                                foreach ($error as $message)
                                                {
                                                    $mensaje .= ($attribute.": ".$message . "<br/>");
                                                }
                                            }
                                            //echo $mensaje;
                                        }

                                        //$xml->save();

                                        //CODIGO PARA RECIBOS DE MERCADERIAS RECIBIDOS
                                        if($xml->getElementsByTagName("DocumentoRecibo")->length >= 1){

                                            $xml->save(Constantes::systemPath . "empresas/$pRutEmpresa/recepciones/RMR_".$caratula_respuesta->idcaratula_respuesta_recibida . "_" . $caratula_respuesta->NombreArchivo);
                                            $caratula_respuesta->TipoRespuesta = Constantes::RECIBO_MERCADERIAS;
                                            $caratula_respuesta->update();
                                            if($valid){
                                                $DocumentoRecibo_node = $xml->getElementsByTagName("DocumentoRecibo")->item(0);
                                                $recibos_mercaderias = new RecibosMercaderiasRecibidos();
                                                $recibos_mercaderias->idemisor = $datos_email->idemisor;
                                                $recibos_mercaderias->idcaratula_respuesta_recibida = $caratula_respuesta->idcaratula_respuesta_recibida;
                                                $recibos_mercaderias->Recinto = $DocumentoRecibo_node->getElementsByTagName("Recinto")->item(0)->nodeValue;
                                                $recibos_mercaderias->RutFirma = $DocumentoRecibo_node->getElementsByTagName("RutFirma")->item(0)->nodeValue;

                                                $tipo_dte = $DocumentoRecibo_node->getElementsByTagName("TipoDoc")->item(0)->nodeValue;
                                                $Folio = $DocumentoRecibo_node->getElementsByTagName("Folio")->item(0)->nodeValue;
                                                $FchEmis = $DocumentoRecibo_node->getElementsByTagName("FchEmis")->item(0)->nodeValue;
                                                $RutEmisor = $DocumentoRecibo_node->getElementsByTagName("RUTEmisor")->item(0)->nodeValue;
                                                $RutRecep = $DocumentoRecibo_node->getElementsByTagName("RUTRecep")->item(0)->nodeValue;
                                                $MntTotal = $DocumentoRecibo_node->getElementsByTagName("MntTotal")->item(0)->nodeValue;

                                                $tipos_documentos = new TiposDocumentos();
                                                $tipos_documentos = $tipos_documentos->model()->findByAttributes(array('dtevalue'=>$tipo_dte));

                                                $documento_respuesta = new DocumentosTributarios();
                                                $documento_respuesta = $documento_respuesta->model()->findByAttributes(array('Folio'=>$Folio,
                                                    'idtipo_documento'=>$tipos_documentos->idtipo_documento,
                                                    'FchEmis'=>$FchEmis,
                                                    'RUTEmisor'=>$RutEmisor,
                                                    'RutRecep'=>$RutRecep,
                                                    'MntTotal'=>$MntTotal));
                                                if($documento_respuesta != NULL){
                                                    $recibos_mercaderias->iddocumento_tributario = $documento_respuesta->iddocumento_tributario;
                                                    if($recibos_mercaderias->save()){
                                                        $valid = $valid && true;
                                                    }else{
                                                        $mensaje = "";
                                                        foreach ($recibos_mercaderias->getErrors() as $attribute => $error)
                                                        {
                                                            foreach ($error as $message)
                                                            {
                                                                $mensaje .= ($attribute.": ".$message . "<br/>");
                                                            }
                                                        }
                                                        //echo $mensaje;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    break;

                                case 'RespuestaDTE':
                                    $RutRecibe = $xml->getElementsByTagName("RutRecibe")->item(0)->nodeValue;
                                    $RutResponde = $xml->getElementsByTagName("RutResponde")->item(0)->nodeValue;

                                    if($xml->getElementsByTagName("NmbContacto")->length == 1){
                                        $NmbContacto = $xml->getElementsByTagName("NmbContacto")->item(0)->nodeValue;
                                    }else{
                                        $NmbContacto = NULL;
                                    }

                                    if($xml->getElementsByTagName("FonoContacto")->length == 1){
                                        $FonoContacto = $xml->getElementsByTagName("FonoContacto")->item(0)->nodeValue;
                                    }else{
                                        $FonoContacto = NULL;
                                    }

                                    if($xml->getElementsByTagName("MailContacto")->length == 1){
                                        $MailContacto = $xml->getElementsByTagName("MailContacto")->item(0)->nodeValue;
                                    }else{
                                        $MailContacto = NULL;
                                    }

                                    if($xml->getElementsByTagName("TmstFirmaResp")->length == 1){
                                        $TmstFirmaResp = $xml->getElementsByTagName("TmstFirmaResp")->item(0)->nodeValue;
                                    }else{
                                        $TmstFirmaResp = "";
                                    }

                                    if($RutRecibe == $datos_email->rut){
                                        $valid = true;
                                        $caratula_respuesta = new CaratulasRespuestasRecibidas();
                                        $caratula_respuesta->idemisor = $datos_email->idemisor;
                                        $caratula_respuesta->RutResponde = $RutResponde;
                                        $caratula_respuesta->RutRecibe = $RutRecibe;
                                        $caratula_respuesta->NmbContacto = $NmbContacto;
                                        $caratula_respuesta->FonoContacto = $FonoContacto;
                                        $caratula_respuesta->MailContacto = $MailContacto;
                                        $caratula_respuesta->TmstFirma = $TmstFirmaResp;
                                        $caratula_respuesta->NombreArchivo = $adjunto['filename'];
                                        //$caratula_respuesta->Archivo = base64_encode($adjunto['attachment']);
                                        if($caratula_respuesta->NombreArchivo == ""){
                                            $caratula_respuesta->NombreArchivo = ".xml";
                                        }

                                        $caratula_respuesta->create_date = date('Y-m-d H:i:s');
                                        $pRutEmpresa= substr($datos_email->rut,0, -2);
                                        $mensaje = "";

                                        if($caratula_respuesta->save()){
                                            $valid = $valid && true;
                                        }else{
                                            $valid = $valid && false;
                                            foreach ($caratula_respuesta->getErrors() as $attribute => $error)
                                            {
                                                foreach ($error as $message)
                                                {
                                                    $mensaje .= ($attribute.": ".$message . "<br/>");
                                                }
                                            }
                                            echo $mensaje;
                                        }

                                        //CODIGO PARA RECEPCIONES ENVIOS RECIBIDOS
                                        if($xml->getElementsByTagName("RecepcionEnvio")->length >= 1){
                                            $xml->save(Constantes::systemPath . "empresas/$pRutEmpresa/recepciones/RER_".$caratula_respuesta->idcaratula_respuesta_recibida . "_" . $caratula_respuesta->NombreArchivo);
                                            $caratula_respuesta->TipoRespuesta = Constantes::RESPUESTA_RECEPCION;
                                            $caratula_respuesta->update();

                                            if($valid){
                                                $RecepcionEnvio_node = $xml->getElementsByTagName("RecepcionEnvio")->item(0);
                                                $RecepcionDTE_nodes = $RecepcionEnvio_node->getElementsByTagName("RecepcionDTE");
                                                $EnvioDTEID = $RecepcionEnvio_node->getElementsByTagName("EnvioDTEID")->item(0)->nodeValue;

                                                if($RecepcionEnvio_node->getElementsByTagName("RutEmisor")->length == 1){
                                                    $RutEmisor = $RecepcionEnvio_node->getElementsByTagName("RutEmisor")->item(0)->nodeValue;
                                                }else{
                                                    $RutEmisor = "";
                                                }

                                                if($RecepcionEnvio_node->getElementsByTagName("RutReceptor")->length == 1){
                                                    $RutReceptor = $RecepcionEnvio_node->getElementsByTagName("RutReceptor")->item(0)->nodeValue;
                                                }else{
                                                    $RutReceptor = "";
                                                }

                                                $EstadoRecepEnv = $RecepcionEnvio_node->getElementsByTagName("EstadoRecepEnv")->item(0)->nodeValue;
                                                $RecepEnvGlosa = $RecepcionEnvio_node->getElementsByTagName("RecepEnvGlosa")->item(0)->nodeValue;

                                                $env_dte = new EnviosDtes();
                                                $env_dte = $env_dte->model()->findByAttributes(array('RutEmisor'=>$RutEmisor,
                                                    'RutReceptor'=>$RutReceptor,
                                                    'SetDTE_ID'=>$EnvioDTEID));

                                                $Recepcion_envios_recibidos = new RecepcionEnviosRecibidos();
                                                $Recepcion_envios_recibidos->idemisor = $datos_email->idemisor;
                                                $Recepcion_envios_recibidos->idcaratula_respuesta_recibida = $caratula_respuesta->idcaratula_respuesta_recibida;
                                                $Recepcion_envios_recibidos->EstadoRecepEnv = $EstadoRecepEnv;
                                                $Recepcion_envios_recibidos->RecepEnvGlosa = $RecepEnvGlosa;

                                                if($env_dte != NULL){
                                                    $Recepcion_envios_recibidos->idenvio_dte = $env_dte->idenvio_dte;
                                                    if($Recepcion_envios_recibidos->save()){
                                                        $valid = $valid && true;

                                                        foreach($RecepcionDTE_nodes as $RecepcionDTE_node){

                                                            $recepciones_dte_recibidos = new RecepcionesDteRecibidos();
                                                            $recepciones_dte_recibidos->idemisor = $datos_email->idemisor;
                                                            $recepciones_dte_recibidos->idrecepcion_envio_recibido = $Recepcion_envios_recibidos->idrecepcion_envio_recibido;
                                                            $recepciones_dte_recibidos->EstadoRecepDTE = $RecepcionDTE_node->getElementsByTagName("EstadoRecepDTE")->item(0)->nodeValue;
                                                            $recepciones_dte_recibidos->RecepDTEGlosa = $RecepcionDTE_node->getElementsByTagName("RecepDTEGlosa")->item(0)->nodeValue;

                                                            $tipo_dte = $RecepcionDTE_node->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
                                                            $Folio = $RecepcionDTE_node->getElementsByTagName("Folio")->item(0)->nodeValue;
                                                            $FchEmis = $RecepcionDTE_node->getElementsByTagName("FchEmis")->item(0)->nodeValue;
                                                            $RutEmisor = $RecepcionDTE_node->getElementsByTagName("RUTEmisor")->item(0)->nodeValue;
                                                            $RutRecep = $RecepcionDTE_node->getElementsByTagName("RUTRecep")->item(0)->nodeValue;
                                                            $MntTotal = $RecepcionDTE_node->getElementsByTagName("MntTotal")->item(0)->nodeValue;

                                                            $tipos_documentos = new TiposDocumentos();
                                                            $tipos_documentos = $tipos_documentos->model()->findByAttributes(array('dtevalue'=>$tipo_dte));

                                                            $documento_respuesta = new DocumentosTributarios();
                                                            $documento_respuesta = $documento_respuesta->model()->findByAttributes(array('Folio'=>$Folio,
                                                                'idtipo_documento'=>$tipos_documentos->idtipo_documento,
                                                                'FchEmis'=>$FchEmis,
                                                                'RUTEmisor'=>$RutEmisor,
                                                                'RutRecep'=>$RutRecep,
                                                                'MntTotal'=>$MntTotal));

                                                            if($documento_respuesta != NULL){
                                                                $recepciones_dte_recibidos->iddocumento_tributario = $documento_respuesta->iddocumento_tributario;
                                                                if($recepciones_dte_recibidos->save()){
                                                                    $valid = $valid && true;
                                                                }else{
                                                                    $mensaje = "";
                                                                    foreach ($recepciones_dte_recibidos->getErrors() as $attribute => $error)
                                                                    {
                                                                        foreach ($error as $message)
                                                                        {
                                                                            $mensaje .= ($attribute.": ".$message . "<br/>");
                                                                        }
                                                                    }
                                                                    echo $mensaje;
                                                                }
                                                            }
                                                        }

                                                    }else{
                                                        $valid = $valid && false;
                                                        $mensaje = "";
                                                        foreach ($Recepcion_envios_recibidos->getErrors() as $attribute => $error)
                                                        {
                                                            foreach ($error as $message)
                                                            {
                                                                $mensaje .= ($attribute.": ".$message . "<br/>");
                                                            }
                                                        }
                                                        echo $mensaje;
                                                    }
                                                }
                                            }
                                        }

                                        //CODIGO PARA RESPUESTAS COMERCIALES RECIBIDAS
                                        if($xml->getElementsByTagName("ResultadoDTE")->length >= 1){
                                            $xml->save(Constantes::systemPath . "empresas/$pRutEmpresa/recepciones/RCR_".$caratula_respuesta->idcaratula_respuesta_recibida . "_" . $caratula_respuesta->NombreArchivo);
                                            $valid = true;
                                            $caratula_respuesta->TipoRespuesta = Constantes::RESULTADO_DTE;
                                            $caratula_respuesta->update();

                                            if($valid){
                                                $ResultadoDTE_nodes = $xml->getElementsByTagName("ResultadoDTE");

                                                foreach($ResultadoDTE_nodes as $ResultadoDTE_node){

                                                    $respuestas_dte = new RespuestasComercialesRecibidas();
                                                    $respuestas_dte->idemisor = $datos_email->idemisor;
                                                    $respuestas_dte->idcaratula_respuesta_recibida = $caratula_respuesta->idcaratula_respuesta_recibida;
                                                    $respuestas_dte->CodEnvio = $ResultadoDTE_node->getElementsByTagName("CodEnvio")->item(0)->nodeValue;
                                                    $respuestas_dte->EstadoDTE = $ResultadoDTE_node->getElementsByTagName("EstadoDTE")->item(0)->nodeValue;
                                                    $respuestas_dte->EstadoDTEGlosa = $ResultadoDTE_node->getElementsByTagName("EstadoDTEGlosa")->item(0)->nodeValue;

                                                    $tipo_dte = $ResultadoDTE_node->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
                                                    $Folio = $ResultadoDTE_node->getElementsByTagName("Folio")->item(0)->nodeValue;
                                                    $FchEmis = $ResultadoDTE_node->getElementsByTagName("FchEmis")->item(0)->nodeValue;
                                                    $RutEmisor = $ResultadoDTE_node->getElementsByTagName("RUTEmisor")->item(0)->nodeValue;
                                                    $RutRecep = $ResultadoDTE_node->getElementsByTagName("RUTRecep")->item(0)->nodeValue;
                                                    $MntTotal = $ResultadoDTE_node->getElementsByTagName("MntTotal")->item(0)->nodeValue;

                                                    $tipos_documentos = new TiposDocumentos();
                                                    $tipos_documentos = $tipos_documentos->model()->findByAttributes(array('dtevalue'=>$tipo_dte));

                                                    $documento_respuesta = new DocumentosTributarios();
                                                    $documento_respuesta = $documento_respuesta->model()->findByAttributes(array('Folio'=>$Folio,
                                                        'idtipo_documento'=>$tipos_documentos->idtipo_documento,
                                                        'FchEmis'=>$FchEmis,
                                                        'RUTEmisor'=>$RutEmisor,
                                                        'RutRecep'=>$RutRecep,
                                                        'MntTotal'=>$MntTotal));

                                                    if($documento_respuesta != NULL){
                                                        $respuestas_dte->iddocumento_tributario = $documento_respuesta->iddocumento_tributario;
                                                        $respuestas_dte->save();
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    break;

                                case 'RESULTADO_ENVIO':

                                    $identificacion = $xml->getElementsByTagName("IDENTIFICACION")->item(0);

                                    if ($identificacion->getElementsByTagName("RUTEMISOR")->length == 1) {
                                        $rut_emisor = $identificacion->getElementsByTagName("RUTEMISOR")->item(0)->nodeValue;
                                    }

                                    if ($identificacion->getElementsByTagName("RUTENVIA")->length == 1) {
                                        $rut_envia = $identificacion->getElementsByTagName("RUTENVIA")->item(0)->nodeValue;
                                    }

                                    if ($identificacion->getElementsByTagName("TRACKID")->length == 1) {
                                        $trackid = $identificacion->getElementsByTagName("TRACKID")->item(0)->nodeValue;
                                    }

                                    if ($identificacion->getElementsByTagName("ESTADO")->length == 1) {
                                        $estado = $identificacion->getElementsByTagName("ESTADO")->item(0)->nodeValue;
                                    }

                                    if($estado == "RSC - Rechazado por Error en Schema"){
                                        $no_valido = 1;
                                    }else{
                                        $no_valido = 0;
                                    }

                                    if($rut_emisor != $datos_email->rut){
                                        break;
                                    }

                                    $env_dte = new EnviosDtes();
                                    $env_dte = $env_dte->model()->findByAttributes(array('RutEmisor'=>$rut_emisor,'RutEnvia'=>$rut_envia,
                                        'trackid'=>$trackid));
                                    if($env_dte != NULL){
                                        $env_dte->glosa = $estado;
                                        $env_dte->update();
                                    }

                                    if($xml->getElementsByTagName("ESTADISTICA")->length > 0){
                                        $estadisticas = $xml->getElementsByTagName("ESTADISTICA")->item(0);
                                        $subtotales = $estadisticas->getElementsByTagName("SUBTOTAL");

                                        foreach($subtotales as $subtotal){
                                            $resultado_envio = new EstadisticasEnvios();
                                            $resultado_envio->idenvio_dte = $env_dte->idenvio_dte;
                                            $resultado_envio->idemisor = $env_dte->idemisor;

                                            $tipodoc = "";
                                            $informado = 0;
                                            $acepta = 0;
                                            $rechazo = 0;
                                            $reparo = 0;

                                            if ($subtotal->getElementsByTagName("TIPODOC")->length == 1) {
                                                $tipodoc = $subtotal->getElementsByTagName("TIPODOC")->item(0)->nodeValue;
                                            }

                                            if ($subtotal->getElementsByTagName("INFORMADO")->length == 1) {
                                                $informado = $subtotal->getElementsByTagName("INFORMADO")->item(0)->nodeValue;
                                            }

                                            if ($subtotal->getElementsByTagName("ACEPTA")->length == 1) {
                                                $acepta = $subtotal->getElementsByTagName("ACEPTA")->item(0)->nodeValue;
                                            }

                                            if ($subtotal->getElementsByTagName("RECHAZO")->length == 1) {
                                                $rechazo = $subtotal->getElementsByTagName("RECHAZO")->item(0)->nodeValue;
                                            }

                                            if ($subtotal->getElementsByTagName("REPARO")->length == 1) {
                                                $reparo = $subtotal->getElementsByTagName("REPARO")->item(0)->nodeValue;
                                            }

                                            if($informado == 1 && ($acepta == 1 || $reparo == 1)){

                                            }

                                            $resultado_envio->tipodoc = $tipodoc;
                                            $resultado_envio->informado = $informado;
                                            $resultado_envio->acepta = $acepta;
                                            $resultado_envio->rechazo = $rechazo;
                                            $resultado_envio->reparo = $reparo;
                                            $resultado_envio->save();

                                        }

                                    }

                                    if($xml->getElementsByTagName("ERRORENVIO")->length > 0){
                                        $errorenvio = $xml->getElementsByTagName("ERRORENVIO")->item(0);
                                        $deterrenvios = $errorenvio->getElementsByTagName("DETERRENVIO");

                                        foreach($deterrenvios as $deterrenvio){
                                            TrackingSystem::generarLog($env_dte->idenvio_dte, 7, $deterrenvio->nodeValue, 1);
                                        }

                                    }

                                    if($no_valido){
                                        continue;
                                    }

                                    $folios_array = array();
                                    if($xml->getElementsByTagName("REVISIONENVIO")->length > 0){
                                        $revisiones_envios = $xml->getElementsByTagName("REVISIONENVIO")->item(0);
                                        $revisiones_dtes = $revisiones_envios->getElementsByTagName("REVISIONDTE");


                                        foreach($revisiones_dtes as $revision_dte){
                                            $revision = new RevisionesDtes();
                                            $revision->idenvio_dte = $env_dte->idenvio_dte;
                                            $revision->idemisor = $env_dte->idemisor;

                                            $folio = "";
                                            $tipodte = "";
                                            $estado = "";
                                            $detalle = "";

                                            if ($revision_dte->getElementsByTagName("FOLIO")->length == 1) {
                                                $folio = $revision_dte->getElementsByTagName("FOLIO")->item(0)->nodeValue;
                                                $folio = intval($folio);
                                            }

                                            if ($revision_dte->getElementsByTagName("TIPODTE")->length == 1) {
                                                $tipodte = $revision_dte->getElementsByTagName("TIPODTE")->item(0)->nodeValue;
                                            }

                                            if ($revision_dte->getElementsByTagName("ESTADO")->length == 1) {
                                                $estado = $revision_dte->getElementsByTagName("ESTADO")->item(0)->nodeValue;
                                            }

                                            $revision->folio = $folio;
                                            $revision->tipodte = $tipodte;
                                            $revision->estado = $estado;

                                            $folio_array = array('Folio'=>$folio,'TipoDTE'=>$tipodte, 'Estado'=>$estado);
                                            array_push($folios_array, $folio_array);

                                            if($revision->save()){
                                                $valid = $valid && true;
                                            }else{
                                                $valid = $valid && false;
                                                foreach ($revision->getErrors() as $attribute => $error)
                                                {
                                                    foreach ($error as $message)
                                                    {
                                                        echo ($attribute.": ".$message . "<br/>");
                                                    }
                                                }
                                            }


                                            if($revision_dte->getElementsByTagName("DETALLE")->length > 0){
                                                $detalles_dte = $revision_dte->getElementsByTagName("DETALLE");
                                                $detalle_email ="<html>
                                                    <head>
                                                    <style type='text/css'>
                                                    table {
                                                        border-collapse: collapse;
                                                        width: 100%;
                                                    }

                                                    th, td {
                                                        text-align: left;
                                                        padding: 8px;
                                                    }

                                                    tr:nth-child(even){background-color: #f2f2f2}

                                                    th {
                                                        background-color: #4CAF50;
                                                        color: white;
                                                    }
                                                    </style>
                                                    </head>";
                                                $detalle_email .= "<table border='1'><tr><th>Folio</th><th>Tipo DTE</th><th>Estado</th><th>Detalle</th></tr>";

                                                foreach($detalles_dte as $detalle_dte){
                                                    $detalles_revision = new DetallesRevision();
                                                    $detalles_revision->idrevision_dte = $revision->idrevision_dte;
                                                    $detalles_revision->idemisor = $env_dte->idemisor;
                                                    $detalles_revision->detalle = $detalle_dte->nodeValue;

                                                    if($detalles_revision->save()){
                                                        $valid = $valid && true;
                                                    }else{
                                                        $valid = $valid && false;
                                                        foreach ($detalles_revision->getErrors() as $attribute => $error)
                                                        {
                                                            foreach ($error as $message)
                                                            {
                                                                echo ($attribute.": ".$message . "<br/>");
                                                            }
                                                        }
                                                    }

                                                    $detalle_email .= "<tr><td>$revision->folio</td><td>$revision->tipodte</td><td>$revision->estado</td><td>$detalles_revision->detalle</td></tr>";

                                                }

                                                $detalle_email .="</table></html>";
                                                $mail_reparo = crearObjetoMail($revision->idemisor);

                                                $parametros_copia = new ParametrosEmisores();
                                                $parametros_copia = $parametros_copia->model()->findAll("nombre = 'copias_cron' AND idemisor = '$revision->idemisor'");

                                                if(count($parametros_copia) > 0){
                                                    foreach($parametros_copia as $parametro_copia){
                                                        $mail_reparo->addAddress($parametro_copia->valor);
                                                    }
                                                }

                                                $mail_reparo->Subject = "REPARO EN DTES ( $datos_email->razon_social )";
                                                $mail_reparo->msgHTML($detalle_email);
                                                //send the message, check for errors
                                                if (!$mail_reparo->send()) {
                                                    //$mensaje_log = "Error al enviar XML al contribuyente receptor ($correo) <br/>" . "Mensaje de error: $mail->ErrorInfo";
                                                    echo "Mailer Error: " . $mail_reparo->ErrorInfo;
                                                    //$mensaje .= "Error al enviar el correo $mail->ErrorInfo<br/>";

                                                } else {
                                                    echo "LOG DE REPAROS ENVIADO\r\n";
                                                    //$mensaje_log = "Se envio XML al contribuyente receptor ($correo)";
                                                }

                                            }

                                            $tipos_documentos = new TiposDocumentos();
                                            $tipos_documentos = $tipos_documentos->model()->findByAttributes(array('dtevalue'=>$tipodte));

                                            $dte = new DocumentosTributarios();
                                            $dte = $dte->model()->findByAttributes(array('Folio'=>$folio,'idtipo_documento'=>$tipos_documentos->idtipo_documento));

                                            if($dte != NULL){
                                                $dte->glosa_estado = $estado;
                                                $dte->glosa_err = $detalle;
                                                $dte->update();
                                            }
                                        }
                                    }

                                    $arreglo_envio = array();
                                    foreach($env_dte->documentosTributarios as $dte_enviar){
                                        array_push($arreglo_envio, array('EC'=>$dte_enviar->envio_contribuyente, 'TipoDTE'=>$dte_enviar->TipoDTE, 'Folio'=>$dte_enviar->Folio, 'Marca'=>0, 'Estado'=>'', 'id'=>$dte_enviar->iddocumento_tributario));
                                    }

                                    foreach($arreglo_envio as &$arreglo){
                                        foreach($folios_array as $arreglo_folio){
                                            if($arreglo['TipoDTE'] == $arreglo_folio['TipoDTE'] && $arreglo['Folio'] == $arreglo_folio['Folio']){
                                                $arreglo['Marca'] = 1;
                                                $arreglo['Estado'] = $arreglo_folio['Estado'];
                                            }
                                        }
                                    }

                                    foreach($arreglo_envio as $arregloEnviar){
                                        $envio_enviar = new EnviosDtes();
                                        $envio_enviar = $envio_enviar->model()->findByPk($arregloEnviar['EC']);
                                        if($arregloEnviar['Estado'] != "RCH - DTE Rechazado"){
                                            $parametros = new ParametrosEmisores();
                                            $parametros = $parametros->model()->find("nombre = 'produccion' AND idemisor = '$datos_email->idemisor'");

                                            if(isset($parametros) && $parametros->valor == "1"){
                                                $envio_enviar->enviarAlReceptor(1);
                                                echo "Se esta enviando el envio " . $arregloEnviar['EC'] . "<br></br>\r\n";
                                            }else{
                                                echo "No se envian documentos en modo certificacion.<br></br>\r\n";
                                            }
                                        }
                                        $dte_enviar = DocumentosTributarios::model()->findByPk($arregloEnviar['id']);
                                        $dte_enviar->glosa_estado = "DTE Recibido";
                                        $dte_enviar->glosa_err = "Documento Recibido por el SII. Datos Coinciden con los Registrados";

                                        if($arregloEnviar['Marca'] == 1){
                                            $dte_enviar->glosa_estado = $arregloEnviar['Estado'];
                                            $dte_enviar->glosa_err = "Documento Recibido por el SII. Recibido con Reparos";
                                        }

                                        if($arregloEnviar['Estado'] == "RCH - DTE Rechazado"){
                                            $dte_enviar->glosa_estado = $arregloEnviar['Estado'];
                                            $dte_enviar->glosa_err = "Documento NO Recibido por el SII.";
                                        }

                                        $dte_enviar->update();
                                    }

                                    break;

                                case 'EnvioDTE':
                                    $EnvioDTE++;
                                    $EsValido = true;
                                    $errores_esquema = "";
                                    $SchemaError = false;

                                    if (EnvioDtesEsNoValido($xml, $errores_esquema))
                                    {
                                        $EnvioDTE--;
                                        $EsValido = false;
                                        $SchemaError = true;
                                        $EstadoRecepEnv = 1;
                                        $RecepEnvGlosa = getRecepEnvGlosa($EstadoRecepEnv);
                                    }

                                    //else{

                                    if($EsValido){
                                        $validador = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();

                                        if(true !== $validador->verify($xmlToVerify)){
                                            $EstadoRecepEnv = 2;
                                            $EsValido = false;
                                            $RecepEnvGlosa = getRecepEnvGlosa($EstadoRecepEnv);
                                        }

                                        $EstadoRecepEnv = 0;
                                        $RecepEnvGlosa = getRecepEnvGlosa($EstadoRecepEnv);
                                        $countDigestValue = $xml->getElementsByTagName("DigestValue")->length;
                                        $digestCount = $countDigestValue - 1;

                                    }

                                    $SetDTE = $xml->getElementsByTagName('SetDTE')->item(0);
                                    $SetDTEId = $SetDTE->getAttribute('ID');
                                    $doc = $xml->getElementsByTagName("DTE");
                                    $i = 0;
                                    foreach($doc as $dte){
                                        $i++;
                                    }

                                    $recep_env = new RecepcionEnvio;

                                    if($rutEmisor != ""){
                                        $recep_env->idreceptor = $datos_email->idemisor;
                                    }else{
                                        $recep_env->idreceptor = Yii::app()->session['idemisor'];
                                    }

                                    $timezone = new DateTimeZone('America/Santiago');
                                    $date = new DateTime('', $timezone);
                                    $fchRecep = $date->format('Y-m-d H:i:s');

                                    $recep_env->NmbEnvio = utf8_encode($adjunto['filename']);
                                    $recep_env->FchRecep = $fchRecep;
                                    $recep_env->EnvioDTEID = $SetDTEId;
                                    $recep_env->RutEmisor = $xml->getElementsByTagName("RutEmisor")->item(0)->nodeValue;
                                    $recep_env->RutEnvia = $xml->getElementsByTagName("RutEnvia")->item(0)->nodeValue;
                                    $recep_env->RutReceptor = $xml->getElementsByTagName("RutReceptor")->item(0)->nodeValue;
                                    $recep_env->EstadoRecepEnv = $EstadoRecepEnv;
                                    $recep_env->RecepEnvGlosa = $RecepEnvGlosa;
                                    $recep_env->TmstFirmaEnv = $xml->getElementsByTagName("TmstFirmaEnv")->item(0)->nodeValue;
                                    $recep_env->Digest = $xml->getElementsByTagName("DigestValue")->item($digestCount)->nodeValue;
                                    $recep_env->correorespuesta = $reply_to;
                                    $recep_env->NroDTE = $i;

                                    $recepcionENVIO = new RecepcionEnvio;
                                    $boleanRE = $recepcionENVIO->model()->exists("Digest = '$recep_env->Digest'");

                                    if($boleanRE){
                                        $EstadoRecepEnv = 90;
                                        $RecepEnvGlosa = getRecepEnvGlosa($EstadoRecepEnv);
                                    }

                                    if($rutEmisor != ""){
                                        $receptor = Emisores::model()->findByPK($datos_email->idemisor);
                                    }else{
                                        $receptor = Emisores::model()->findByPK(Yii::app()->session['idemisor']);
                                    }

                                    if($receptor !== NULL){
                                        $pRutEmpresa_validate = substr($recep_env->RutReceptor,0, -2);
                                        $pRutEmpresa_validate = (int)$pRutEmpresa_validate;
                                        $pDigEmpresa_validate  = substr ($recep_env->RutReceptor, -1);
                                        $RutComparar = $pRutEmpresa_validate . "-" . $pDigEmpresa_validate;

                                        if($receptor->rut != $RutComparar){
                                            throw new Exception("El Rut receptor no corresponder a la empresa.");
                                        }
                                    }

                                    if($recep_env->save()){

                                        if($errores_esquema != ""){
                                            TrackingSystem::generarLog($recep_env->idrecepcion_envio, 3, $errores_esquema, 1);
                                        }

                                        $recep_env->CodEnvio = $recep_env->idrecepcion_envio;
                                        $pRutEmpresa = substr($recep_env->receptorFK->rut,0, -2);
                                        //$recep_env->Archivo_recibido = base64_encode($xml->saveXML());
                                        $recep_env->Archivo_recibido = Constantes::systemPath . "empresas/$pRutEmpresa/recepciones/RE_" . $recep_env->idrecepcion_envio . "_" .utf8_encode($adjunto['filename']);
                                        $recep_env->update();

                                        $xml->save(Constantes::systemPath . "empresas/$pRutEmpresa/recepciones/RE_".$recep_env->idrecepcion_envio."_".utf8_encode($adjunto['filename']));

                                        /* AQUÍ SE CREA LA ASOCIACION ENTRE LA RESPUESTA Y EL ENVIO*/
                                        $respuesta_dte = new RespuestasDte();
                                        $respuesta_dte->idrecepcion_envio = $recep_env->idrecepcion_envio;
                                        $respuesta_dte->idreceptor = $receptor->idemisor;
                                        $respuesta_dte->tipo_respuesta = Constantes::RESPUESTA_RECEPCION;

                                        if($respuesta_dte->save()){
                                            $valid = $valid && true;
                                        }else{
                                            unlink(Constantes::systemPath . "empresas/$pRutEmpresa/recepciones/RE_". $recep_env->idrecepcion_envio . "_" . utf8_encode($adjunto['filename']));
                                        }
                                        /* AQUÍ SE CREA LA ASOCIACION ENTRE LA RESPUESTA Y EL ENVIO*/
                                    }else{

                                        foreach ($recep_env->getErrors() as $attribute => $error){
                                            foreach ($error as $message){
                                                $flash .= ($attribute.": ".$message . "<br/>");
                                            }
                                        }


                                        if($rutEmisor != ""){
                                            echo "$flash\n";
                                        }else{
                                            Yii::app()->user->setFlash('error', $flash);
                                        }

                                        $valid = $valid && false;
                                        throw new Exception('No se logro terminar de guardar el envio');
                                    }

                                    if(!$valid){
                                        //$transaction->rollback();
                                        throw new Exception('No se logro terminar de guardar el envio');
                                    }

                                    $timezone = new DateTimeZone('America/Santiago');
                                    $date = new DateTime('', $timezone);
                                    $fechaTimbre = $date->format('Y-m-d\TH:i:s');

                                    $caratula = new CaratulaRespuesta;
                                    $caratula->idrespuesta_dte = $respuesta_dte->idrespuesta_dte;
                                    $caratula->idreceptor = $receptor->idemisor;
                                    $caratula->tipo_respuesta = Constantes::RESPUESTA_RECEPCION;
                                    $caratula->RutResponde = $receptor->rut;
                                    $caratula->RutRecibe = $recep_env->RutEmisor;
                                    $caratula->NroDetalles = 1;
                                    $caratula->TmstFirmaResp = $fechaTimbre;
                                    $caratula->save();

                                    $XMLRESPUESTA = new XMLWriter();
                                    touch(Constantes::systemPath . "empresas/$pRutEmpresa/respuestas/RSPENV_".$recep_env->idrecepcion_envio.".xml");
                                    $uri = realpath(Constantes::systemPath . "empresas/$pRutEmpresa/respuestas/RSPENV_".$recep_env->idrecepcion_envio.".xml");
                                    $XMLRESPUESTA->openURI($uri);
                                    $XMLRESPUESTA->setIndent(true);
                                    $XMLRESPUESTA->startDocument('1.0', 'ISO-8859-1');
                                    $XMLRESPUESTA->startElement('RespuestaDTE');
                                    $XMLRESPUESTA->writeAttribute('version', '1.0');
                                    $XMLRESPUESTA->writeAttribute('xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
                                    $XMLRESPUESTA->writeAttribute('xmlns', 'http://www.sii.cl/SiiDte');
                                    $XMLRESPUESTA->writeAttribute('xsi:schemaLocation', 'http://www.sii.cl/SiiDte RespuestaEnvioDTE_v10.xsd');

                                    $XMLRESPUESTA->startElement('Resultado');
                                    $XMLRESPUESTA->writeAttribute('ID', 'Respuesta-'."$caratula->idrespuesta_dte");
                                    $XMLRESPUESTA->startElement('Caratula');
                                    $XMLRESPUESTA->writeAttribute('version', '1.0');
                                    $XMLRESPUESTA->writeElement('RutResponde', $caratula->RutResponde);
                                    $XMLRESPUESTA->writeElement('RutRecibe', $caratula->RutRecibe);
                                    $XMLRESPUESTA->writeElement('IdRespuesta', "$caratula->idrespuesta_dte");
                                    $XMLRESPUESTA->writeElement('NroDetalles', "$caratula->NroDetalles");
                                    $XMLRESPUESTA->writeElement('TmstFirmaResp', "$caratula->TmstFirmaResp");
                                    $XMLRESPUESTA->endElement();

                                    $XMLRESPUESTA->startElement('RecepcionEnvio');
                                    $XMLRESPUESTA->writeElement('NmbEnvio', $adjunto['filename']);
                                    $XMLRESPUESTA->writeElement('FchRecep', $fechaTimbre);
                                    $XMLRESPUESTA->writeElement('CodEnvio', "$recep_env->CodEnvio");
                                    $XMLRESPUESTA->writeElement('EnvioDTEID', $SetDTEId);
                                    $XMLRESPUESTA->writeElement('RutEmisor', "$recep_env->RutEmisor");
                                    $XMLRESPUESTA->writeElement('RutReceptor', $recep_env->RutReceptor);
                                    $XMLRESPUESTA->writeElement('EstadoRecepEnv', "$recep_env->EstadoRecepEnv");
                                    $XMLRESPUESTA->writeElement('RecepEnvGlosa', $recep_env->RecepEnvGlosa);
                                    $XMLRESPUESTA->writeElement('NroDTE', $recep_env->NroDTE);

                                    foreach ($doc as $dte){
                                        $errores_esquema = "";
                                        $dte_xml = new DOMDocument('1.0');
                                        $dte_xml->preserveWhiteSpace = TRUE;
                                        $dte_xml->loadXML($xml->saveXML($dte));
                                        $dte_xml->encoding = "ISO-8859-1";


                                        $dte_xmlToVerify = new DOMDocument('1.0');
                                        //$dte_xmlToVerify->preserveWhiteSpace = TRUE;
                                        //$dte_xmlToVerify->formatOutput = TRUE;
                                        $dte_xmlToVerify->loadXML($xml->saveXML($dte));
                                        //$dte_xmlToVerify->documentElement->removeAttributeNS('http://www.sii.cl/SiiDte', '');
                                        //$dte_xmlToVerify->loadXML($dte_xmlToVerify->saveXML());
                                        $dte_xmlToVerify->encoding = "ISO-8859-1";

                                        $NodoDocumento= $dte_xml->getElementsByTagName('Documento')->item(0);
                                        $DocumentoID = $NodoDocumento->getAttribute('ID');

                                        $recep_dte = new RecepcionDte;
                                        $recep_dte->idrespuesta_dte = $respuesta_dte->idrespuesta_dte;
                                        $recep_dte->idreceptor = $receptor->idemisor;
                                        $recep_dte->TipoDTE = $dte_xml->getElementsByTagName("TipoDTE")->item(0)->nodeValue;
                                        $recep_dte->Folio = $dte_xml->getElementsByTagName("Folio")->item(0)->nodeValue;
                                        $recep_dte->FchEmis = $dte_xml->getElementsByTagName("FchEmis")->item(0)->nodeValue;
                                        $recep_dte->RutEmisor = $dte_xml->getElementsByTagName("RUTEmisor")->item(0)->nodeValue;
                                        $recep_dte->RutRecep = $dte_xml->getElementsByTagName("RUTRecep")->item(0)->nodeValue;
                                        $recep_dte->MntTotal = $dte_xml->getElementsByTagName("MntTotal")->item(0)->nodeValue;
                                        $recep_dte->DigestValue = $dte_xml->getElementsByTagName("DigestValue")->item(0)->nodeValue;
                                        $recep_dte->EstadoRecepDTE =  0;
                                        $recep_dte->RecepDTEGlosa = getRecepDTEGlosa($recep_dte->EstadoRecepDTE);

                                        $existeRecepcionDTE = new RecepcionDte;
                                        $booleanDTE = $existeRecepcionDTE->model()->exists("DigestValue = '$recep_dte->DigestValue'");

                                        if($booleanDTE){
                                            $recep_dte->EstadoRecepDTE =  4;
                                            $recep_dte->RecepDTEGlosa = getRecepDTEGlosa($recep_dte->EstadoRecepDTE);
                                        }

                                        $recep_dte->nombre_archivo = "DTE_E". $recep_dte->RutEmisor ."_" . $DocumentoID ."_F". $recep_dte->Folio .".xml";

                                        if(validaRut($recep_dte->RutEmisor) == false){
                                            $recep_dte->EstadoRecepDTE =  2;
                                            $recep_dte->RecepDTEGlosa = getRecepDTEGlosa($recep_dte->EstadoRecepDTE);
                                        }

                                        if(validaRut($recep_dte->RutRecep) == false){
                                            $recep_dte->EstadoRecepDTE =  3;
                                            $recep_dte->RecepDTEGlosa = getRecepDTEGlosa($recep_dte->EstadoRecepDTE);
                                        }

                                        if($receptor !== NULL){
                                            if($receptor->rut != $recep_dte->RutRecep){
                                                $recep_dte->EstadoRecepDTE =  3;
                                                $recep_dte->RecepDTEGlosa = getRecepDTEGlosa($recep_dte->EstadoRecepDTE);
                                            }
                                        }

                                        $validador = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();

                                        if(true !== $validador->verify($dte_xmlToVerify)){
                                            $recep_dte->EstadoRecepDTE = 1;
                                            $recep_dte->RecepDTEGlosa = getRecepDTEGlosa($recep_dte->EstadoRecepDTE);
                                        }

                                        $dte_ns = new DOMDocument('1.0');
                                        $dte_ns->preserveWhiteSpace = TRUE;
                                        $dte_ns->loadXML($xml->saveXML($dte));
                                        $dte_ns->encoding = "ISO-8859-1";
                                        $dte_ns->createAttributeNS('http://www.sii.cl/SiiDte','xmlns');
                                        $dte_ns->loadXML($dte_ns->saveXML());

                                        if(DteEsNoValido($dte_ns, $errores_esquema)){
                                            $recep_dte->EstadoRecepDTE = 99;
                                            $recep_dte->RecepDTEGlosa = getRecepDTEGlosa($recep_dte->EstadoRecepDTE);
                                        }

                                        if($recep_dte->save()){
                                            $valid = $valid && true;
                                            TrackingSystem::generarLog($recep_dte->idrecepcion_dte, 2, $errores_esquema, 1);
                                        }else{
                                            $valid = $valid && false;
                                        }

                                        $XMLRESPUESTA->startElement('RecepcionDTE');
                                        $XMLRESPUESTA->writeElement('TipoDTE', "$recep_dte->TipoDTE");
                                        $XMLRESPUESTA->writeElement('Folio', "$recep_dte->Folio");
                                        $XMLRESPUESTA->writeElement('FchEmis', $recep_dte->FchEmis);
                                        $XMLRESPUESTA->writeElement('RUTEmisor', $recep_dte->RutEmisor);
                                        $XMLRESPUESTA->writeElement('RUTRecep', $recep_dte->RutRecep);
                                        $XMLRESPUESTA->writeElement('MntTotal', "$recep_dte->MntTotal");
                                        $XMLRESPUESTA->writeElement('EstadoRecepDTE', "$recep_dte->EstadoRecepDTE");
                                        $XMLRESPUESTA->writeElement('RecepDTEGlosa', $recep_dte->RecepDTEGlosa);
                                        $XMLRESPUESTA->endElement();


                                        $dte_xml->save(Constantes::systemPath . "empresas/$pRutEmpresa/dte_recibidos/$recep_dte->nombre_archivo");
                                    }

                                    $XMLRESPUESTA->endElement();//FIN DE RECEPCION ENVIO
                                    $XMLRESPUESTA->endElement(); //FIN DE RESULTADO
                                    $XMLRESPUESTA->endElement(); // FIN DE RESPUESTADTE

                                    $XMLRESPUESTA->endDocument();
                                    $XMLRESPUESTA->flush();

                                    if(!$valid){
                                        unlink($uri);
                                        //$transaction->rollback();
                                        throw new Exception('No se logro terminar de guardar el envio');
                                    }

                                    $domRespuesta = new DOMDocument();
                                    $domRespuesta->formatOutput = FALSE;
                                    $domRespuesta->preserveWhiteSpace = TRUE;
                                    $domRespuesta->encoding = "ISO-8859-1";
                                    $domRespuesta->load($uri);
                                    $xmlTool = new FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
                                    $pfx = file_get_contents(Constantes::systemPath . "empresas/$pRutEmpresa/certificados/$receptor->certificadoDigital");
                                    $key = array();
                                    openssl_pkcs12_read($pfx, $key, $receptor->password_certificado);
                                    $xmlTool->setPrivateKey($key["pkey"]);
                                    $xmlTool->setpublickey($key["cert"]);
                                    $xmlTool->addTransform(FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
                                    $xmlTool->sign($domRespuesta, "RESPUESTA");
                                    $domRespuesta->save($uri);

                                    //$recep_env->Archivo_respuesta = base64_encode($domRespuesta->saveXML());
                                    $recep_env->Archivo_respuesta = $uri;
                                    $recep_env->update();

                                    $empresas_sii = new EmpresasSii();
                                    $empresas_sii = $empresas_sii->model()->findByAttributes(array('rut'=>$recep_env->RutEmisor));

                                    $parametros = new ParametrosSistema();
                                    $parametros = $parametros->model()->find("nombre = 'produccion'");

                                    if($valid){
                                        $correo = "";
                                        $mail = crearObjetoMail($recep_env->idreceptor);

                                        if($parametros->valor == "1" && $empresas_sii !== NULL){
                                            $mail->addAddress($empresas_sii->mail_intercambio);
                                            $correo .= "<br/>$empresas_sii->mail_intercambio";
                                        }

                                        $parametros_copia = new ParametrosEmisores();
                                        $parametros_copia = $parametros_copia->model()->findAll("nombre = 'copias_xml' AND idemisor = '$receptor->idemisor'");

                                        if(count($parametros_copia) > 0){
                                            foreach($parametros_copia as $parametro_copia){
                                                $correo .= "<br/>$parametro_copia->valor";
                                                $mail->addAddress($parametro_copia->valor);
                                            }
                                        }

                                        if($parametros->valor == "1" && $empresas_sii === NULL){
                                            $mail->addAddress($reply_to);
                                        }

                                        $mail->Subject = 'RESPUESTA RECEPCION ENVIO DTES';
                                        $mensaje = "RESPUESTA AL ENVIO DE DTES A NUESTRA EMPRESA : $receptor->razon_social RUT $receptor->rut";
                                        $mail->msgHTML($mensaje);
                                        $mail->addAttachment($uri, "FF_RSPENV_".$recep_env->idrecepcion_envio.".xml" , 'base64', 'application/octet-stream');

                                        if($parametros->valor == "1"){
                                            //send the message, check for errors
                                            if (!$mail->send()) {
                                                $mensaje_log = "Error al enviar XML al contribuyente receptor ($correo) <br/>" . "Mensaje de error: $mail->ErrorInfo";
                                                //echo "Mailer Error: " . $mail->ErrorInfo;
                                                //$mensaje .= "Error al enviar el correo $mail->ErrorInfo<br/>";

                                            } else {
                                                echo "RESPUESTAS RECEPCION ENVIADAS\r\n";
                                                $mensaje_log = "Se envio XML al contribuyente receptor ($correo)";
                                            }

                                        }else{
                                            $mensaje_log = "No se envian correos en modo certificacion";
                                        }

                                        TrackingSystem::generarLog($recep_env->idrecepcion_envio, 3, $mensaje_log, 1);
                                    }
                                    //}//FIN ELSE EnvioDtesEsNoValido

                                    break;//FIN SWITCH CASE EnvioDTE

                            }//FIN SWITCH

                        }//FIN IF VALID XML

                    }//FIN IF IS ATTACHMENT

                }//FIN FOREACH ATTACHMENT

            }//FIN FOREACH RESULT

        }//FIN IS ARRAY RESULT

        if($valid){
            //$transaction->commit();

            if($rutEmisor != ""){
                $mensajeLogs = "ENVIOS RECEPCIONADOS Y ALMACENADOS EN BASE DE DATOS\n";
                echo $mensajeLogs;

                $fichero = Constantes::systemPath . "protected/extensions/recepcion_correos/log.txt";
                // La nueva persona a añdir al fichero
                $timezone = new DateTimeZone('America/Santiago');
                $date = new DateTime('', $timezone);
                $fecha = $date->format('Y-m-d H:i:s');
                $mensajeLog = $fecha . " - $mensajeLogs";
                // Escribir los contenidos en el fichero,
                // usando la bandera FILE_APPEND para añadir el contenido al final del fichero
                // y la bandera LOCK_EX para evitar que cualquiera escriba en el fichero al mismo tiempo
                file_put_contents($fichero, $mensajeLog, FILE_APPEND | LOCK_EX);
            }else{
                Yii::app()->user->setFlash('success', "ENVIOS RECEPCIONADOS Y ALMACENADOS EN BASE DE DATOS");
            }

        }else{
            //$transaction->rollback();

            if($rutEmisor != ""){
                echo "ERROR AL GUARDAR ENVIOS RECEPCIONADOS\n";
            }else{
                Yii::app()->user->setFlash('error', "ERROR AL GUARDAR ENVIOS RECEPCIONADOS");
            }

        }

    } catch (Exception $e) {
        //$transaction->rollback();
        if($rutEmisor != ""){
            echo $e->getMessage();
        }else{
            Yii::app()->user->setFlash('error', $e->getMessage());
        }

    }

}
?>
