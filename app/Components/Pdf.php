<?php

namespace App\Components;

class Pdf
{
    public static function getBrowser($user_agent)
    {
        if (strpos($user_agent, 'MSIE') !== false) {
            return 'Internet explorer';
        } elseif (strpos($user_agent, 'Trident') !== false) { //IE 11
            return 'Internet explorer';
        } elseif (strpos($user_agent, 'Firefox') !== false) {
            return 'Mozilla Firefox';
        } elseif (strpos($user_agent, 'Chrome') !== false) {
            return 'Google Chrome';
        } elseif (strpos($user_agent, 'Opera Mini') !== false) {
            return 'Opera Mini';
        } elseif (strpos($user_agent, 'Opera') !== false) {
            return 'Opera';
        } elseif (strpos($user_agent, 'Safari') !== false) {
            return 'Safari';
        } else {
            return 'No hemos podido detectar su navegador';
        }
    }

    public static function numtoletras($xcifra)
    {
        $xarray = [0 => 'Cero',
            1 => 'UN', 'DOS', 'TRES', 'CUATRO', 'CINCO', 'SEIS', 'SIETE', 'OCHO', 'NUEVE',
            'DIEZ', 'ONCE', 'DOCE', 'TRECE', 'CATORCE', 'QUINCE', 'DIECISEIS', 'DIECISIETE', 'DIECIOCHO', 'DIECINUEVE',
            'VEINTI', 30 => 'TREINTA', 40 => 'CUARENTA', 50 => 'CINCUENTA', 60 => 'SESENTA', 70 => 'SETENTA', 80 => 'OCHENTA', 90 => 'NOVENTA',
            100 => 'CIENTO', 200 => 'DOSCIENTOS', 300 => 'TRESCIENTOS', 400 => 'CUATROCIENTOS', 500 => 'QUINIENTOS', 600 => 'SEISCIENTOS', 700 => 'SETECIENTOS', 800 => 'OCHOCIENTOS', 900 => 'NOVECIENTOS',
        ];
//
        $xcifra = trim($xcifra);
        $xlength = strlen($xcifra);
        $xpos_punto = strpos($xcifra, '.');
        $xaux_int = $xcifra;
        //$xdecimales = "00";
        if (! ($xpos_punto === false)) {
            if ($xpos_punto == 0) {
                $xcifra = '0'.$xcifra;
                $xpos_punto = strpos($xcifra, '.');
            }
            $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
            //$xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
        }

        $XAUX = str_pad($xaux_int, 18, ' ', STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
        $xcadena = '';
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = 0;
            $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
            $xexit = true; // bandera para controlar el ciclo del While
            while ($xexit) {
                if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
                    break; // termina el ciclo
                }

                $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
                $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
                for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
                    switch ($xy) {
                        case 1: // checa las centenas
                            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas
                            } else {
                                $key = (int) substr($xaux, 0, 3);
                                if (true === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                                    $xseek = $xarray[$key];
                                    $xsub = self::subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                                    if (substr($xaux, 0, 3) == 100) {
                                        $xcadena = ' '.$xcadena.' CIEN '.$xsub;
                                    } else {
                                        $xcadena = ' '.$xcadena.' '.$xseek.' '.$xsub;
                                    }
                                    $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
                                } else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                                    $key = (int) substr($xaux, 0, 1) * 100;
                                    $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                                    $xcadena = ' '.$xcadena.' '.$xseek;
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 0, 3) < 100)
                            break;
                        case 2: // checa las decenas (con la misma lógica que las centenas)
                            if (substr($xaux, 1, 2) < 10) {
                            } else {
                                $key = (int) substr($xaux, 1, 2);
                                if (true === array_key_exists($key, $xarray)) {
                                    $xseek = $xarray[$key];
                                    $xsub = self::subfijo($xaux);
                                    if (substr($xaux, 1, 2) == 20) {
                                        $xcadena = ' '.$xcadena.' VEINTE '.$xsub;
                                    } else {
                                        $xcadena = ' '.$xcadena.' '.$xseek.' '.$xsub;
                                    }
                                    $xy = 3;
                                } else {
                                    $key = (int) substr($xaux, 1, 1) * 10;
                                    $xseek = $xarray[$key];
                                    if (20 == substr($xaux, 1, 1) * 10) {
                                        $xcadena = ' '.$xcadena.' '.$xseek;
                                    } else {
                                        $xcadena = ' '.$xcadena.' '.$xseek.' Y ';
                                    }
                                } // ENDIF ($xseek)
                            } // ENDIF (substr($xaux, 1, 2) < 10)
                            break;
                        case 3: // checa las unidades
                            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada
                            } else {
                                $key = (int) substr($xaux, 2, 1);
                                $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
                                $xsub = self::subfijo($xaux);
                                $xcadena = ' '.$xcadena.' '.$xseek.' '.$xsub;
                            } // ENDIF (substr($xaux, 2, 1) < 1)
                            break;
                    } // END SWITCH
                } // END FOR
                $xi = $xi + 3;
            } // ENDDO

            if (substr(trim($xcadena), -5, 5) == 'ILLON') { // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
                $xcadena .= ' DE';
            }

            if (substr(trim($xcadena), -7, 7) == 'ILLONES') { // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
                $xcadena .= ' DE';
            }

            // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
            if (trim($xaux) != '') {
                switch ($xz) {
                    case 0:
                        if (trim(substr($XAUX, $xz * 6, 6)) == '1') {
                            $xcadena .= 'UN BILLON ';
                        } else {
                            $xcadena .= ' BILLONES ';
                        }

                        break;
                    case 1:
                        if (trim(substr($XAUX, $xz * 6, 6)) == '1') {
                            $xcadena .= 'UN MILLON ';
                        } else {
                            $xcadena .= ' MILLONES ';
                        }

                        break;
                    case 2:
                        if ($xcifra < 1) {
                            //$xcadena = "CERO PESOS $xdecimales/100 M.N.";
                            $xcadena = 'CERO PESOS';
                        }
                        if ($xcifra >= 1 && $xcifra < 2) {
                            //$xcadena = "UN PESO $xdecimales/100 M.N. ";
                            $xcadena = 'UN PESO';
                        }
                        if ($xcifra >= 2) {
                            //$xcadena.= " PESOS $xdecimales/100 M.N. "; //
                            $xcadena .= ' PESOS'; //
                        }

                        break;
                } // endswitch ($xz)
            } // ENDIF (trim($xaux) != "")
            // ------------------      en este caso, para México se usa esta leyenda     ----------------
            $xcadena = str_replace('VEINTI ', 'VEINTI', $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
            $xcadena = str_replace('  ', ' ', $xcadena); // quito espacios dobles
            $xcadena = str_replace('UN UN', 'UN', $xcadena); // quito la duplicidad
            $xcadena = str_replace('  ', ' ', $xcadena); // quito espacios dobles
            $xcadena = str_replace('BILLON DE MILLONES', 'BILLON DE', $xcadena); // corrigo la leyenda
            $xcadena = str_replace('BILLONES DE MILLONES', 'BILLONES DE', $xcadena); // corrigo la leyenda
            $xcadena = str_replace('DE UN', 'UN', $xcadena); // corrigo la leyenda
        } // ENDFOR ($xz)
        return trim($xcadena);
    }

    // END FUNCTION

    public static function subfijo($xx)
    { // esta función regresa un subfijo para la cifra
        $xx = trim($xx);
        $xstrlen = strlen($xx);
        if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3) {
            $xsub = '';
        }
        //
        if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6) {
            $xsub = 'MIL';
        }
        //
        return $xsub;
    }

    public static function generarPdfDte($xmlstring, array $data)
    {
        $altura = 0;

        if (! isset($data['termico'])) {
            $data['termico'] = 0;
        }

        $tmp_dir = sys_get_temp_dir();

        if ($data['termico'] == 1) {
            $altura = 4.7;
            $altura = ($altura * 10) + 10;
            $mpdf = new \Mpdf\Mpdf(['format' => [80, $altura], 'tempDir' => $tmp_dir]);
        } else {
            $mpdf = new \Mpdf\Mpdf(['format' => 'Letter', 'tempDir' => $tmp_dir]);
        }

        $dom = new \DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xmlstring);
        $dom->encoding = 'ISO-8859-1';
        $mntTotal = $dom->getElementsByTagName('MntTotal')->item(0)->nodeValue;
        $nodes = $dom->getElementsByTagName('TED')->item(0);
        $ted = utf8_decode($dom->saveXML($nodes));

        $xsl = new \DOMDocument('1.0');
        $xsl->loadXML($data['path_xsl']);
        $xp = new \XSLTProcessor();
        $xp->importStylesheet($xsl);
        $html = $xp->transformToXml($dom);
        $html = utf8_encode($html);
        $ted = str_replace("'", '&apos;', $ted);

        $barcode = new tcpdf_barcodes_2d($ted, 'PDF417,1,5');
        $pdf417html = $barcode->getBarcodePNG(1.0, 0.6);

        $razon_social = isset($data['razon_social']) ? $data['razon_social'] : $dom->getElementsByTagName('RznSoc')->item(0)->nodeValue;
        $direccion_regional = Sii::getDireccionRegional($dom->getElementsByTagName('CmnaOrigen')->item(0)->nodeValue);
        $html = str_replace('var:numero_resolucion', $data['numero_resolucion'], $html);
        $html = str_replace('var:web_verificacion', $data['web_verificacion'], $html);
        $html = str_replace('var:ano_resolucion', $data['ano_resolucion'], $html);
        $html = str_replace('var:monto_letras', self::numtoletras($mntTotal), $html);
        $html = str_replace('var:razon_social', $razon_social, $html);

        if(isset($data['logo']) && $data['logo'] !== null){
            $html = str_replace('var:logotipo', $data['logo'], $html);
            $html = str_replace('var:logo1', '', $html);
            $html = str_replace('var:logo2', '', $html);
        }else{
            $html = str_replace('var:logo1', '<!--', $html);
            $html = str_replace('var:logo2', '-->', $html);
        }

        $html = str_replace('var:sucursales', $data['sucursales'], $html);
        $html = str_replace('var:unidad_regional', $direccion_regional, $html);
        $html = str_replace('var:barcode', 'data:image/png;base64,'.base64_encode($pdf417html), $html);
        $html = str_replace('var:observaciones_pdf', $data['observaciones'], $html);

        if (($altura != null && $data['termico'] == 1) || ($altura == null && $data['termico'] == 0)) {
            $mpdf->WriteHTML($data['css'], 1);
            $mpdf->WriteHTML($html, 2);
            // SALIDA
            if ($data['termico'] == 1) {
                if (strpos($html, 'id="hoja3"')) {
                    $altura = (count($mpdf->pages) / 3) * 43 + 25;
                    if ($data['TipoDTE'] == 52) {
                        $altura = $altura - 10;
                    }
                } elseif (strpos($html, 'id="hoja2"')) {
                    $altura = (count($mpdf->pages) / 2) * 47 + 30;
                } else {
                    $altura = count($mpdf->pages) * 47;

                    /*if ($data['TipoDTE'] == 52) {
                        $altura = $altura - 30;
                    }*/

                    /*if ($data['TipoDTE'] == 33 && $data['cedible'] == 1) {
                        $altura = $altura - 20;
                    }*/

                    /*if ($data['TipoDTE'] == 33 && $data['cedible'] == 2) {
                        $altura = $altura + 20;
                    }*/

                    $altura = $altura + 30;
                }

                unset($mpdf);

                $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => [80, $altura], 'tempDir' => $tmp_dir]);

                if(isset($_SERVER['HTTP_USER_AGENT'])){
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                }else{
                    $user_agent = "Mozilla Firefox";
                }
                $navegador = self::getBrowser($user_agent);

                if ($navegador == 'Mozilla Firefox') {
                    $altura_sumar = 120;
                } elseif ($navegador == 'Google Chrome') {
                    $altura_sumar = 120;
                } else {
                    $altura_sumar = 120;
                }

                $altura = $altura + $altura_sumar;
                $html = str_replace('<pagebreak>', "<pagebreak sheet-size='80mm ".$altura."mm'>", $html);
                $mpdf->WriteHTML($data['css'], 1);
                $mpdf->WriteHTML($html, 2);
            }
        }

        return $mpdf->Output('doc.pdf', \Mpdf\Output\Destination::STRING_RETURN);
    }
}
