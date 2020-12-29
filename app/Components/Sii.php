<?php

namespace App\Components;

use App\File;
use App\Models\Empresa;
use App\Models\EnvioDte;
use App\Models\EmpresaParametro;
use App\Models\SII\ConsumoFolios\FolioConsumption;
use Freshwork\ChileanBundle\Exceptions\InvalidFormatException;
use Freshwork\ChileanBundle\Rut;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use App\Models\CertificadoEmpresa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Exceptions\HttpResponseException;

class Sii
{
    const USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:61.0) Gecko/20100101 Firefox/61.0';
    const RESPUESTA_RECEPCION = 1;
    const RESULTADO_DTE = 2;
    const RECIBO_MERCADERIAS = 3;
    const DTEAceptadoOKV = 0;
    const DTEAceptadoOKT = 'ACEPTADO OK';
    const DTEAceptadoconDiscrepanciaV = 1;
    const DTEAceptadoconDiscrepanciaT = 'ACEPTADO CON DISCREPANCIAS';
    const DTERechazadoV = 2;
    const DTERechazadoT = 'RECHAZADO';
    const SemillaCertificacion = 'https://maullin.sii.cl/DTEWS/CrSeed.jws?WSDL';
    const SemillaProduccion = 'https://palena.sii.cl/DTEWS/CrSeed.jws?WSDL';
    const SemillaBoletaCertificacion = 'https://apicert.sii.cl/recursos/v1/boleta.electronica.semilla';
    const SemillaBoletaProduccion = 'https://apicert.sii.cl/recursos/v1/boleta.electronica.semilla';
    const TokenCertificacion = 'https://maullin.sii.cl/DTEWS/GetTokenFromSeed.jws?WSDL';
    const TokenProduccion = 'https://palena.sii.cl/DTEWS/GetTokenFromSeed.jws?WSDL';
    const TokenBoletaCertificacion = 'https://apicert.sii.cl/recursos/v1/boleta.electronica.token';
    const TokenBoletaProduccion = 'https://apicert.sii.cl/recursos/v1/boleta.electronica.token';
    const UploadCertificacion = 'https://maullin.sii.cl/cgi_dte/UPL/DTEUpload';
    const UploadProduccion = 'https://palena.sii.cl/cgi_dte/UPL/DTEUpload';
    const UploadBoletaCertificacion = 'https://pangal.sii.cl/recursos/v1/boleta.electronica.envio';
    const UploadBoletaProduccion = 'https://rahue.sii.cl/recursos/v1/boleta.electronica.envio';
    const WSEstadoEnvioCertificacion = 'https://maullin.sii.cl/DTEWS/QueryEstUp.jws?WSDL';
    const WSEstadoEnvioProduccion = 'https://palena.sii.cl/DTEWS/QueryEstUp.jws?WSDL';
    const WSEstadoDTECertificacion = 'https://maullin.sii.cl/DTEWS/QueryEstDte.jws?WSDL';
    const WSEstadoDTEProduccion = 'https://palena.sii.cl/DTEWS/QueryEstDte.jws?WSDL';
    const WSSRCorreoValidacionCertificacion = 'https://maullin.sii.cl/DTEWS/services/wsDTECorreo?wsdl';
    const WSSRCorreoValidacionProduccion = 'https://palena.sii.cl/DTEWS/services/wsDTECorreo?wsdl';
    const WSRegistroReclamoCertificacion = 'https://ws2.sii.cl/WSREGISTRORECLAMODTECERT/registroreclamodteservice?wsdl';
    const WSRegistroReclamoProduccion = 'https://ws1.sii.cl/WSREGISTRORECLAMODTE/registroreclamodteservice?wsdl';
    const DTESRecibidosProduccion = 'https://palena.sii.cl/cgi_dte/consultaDTE/wsDTEConsRecCont.sh?';
    const DTESRecibidosCertificacion = 'https://maullin.sii.cl/cgi_dte/consultaDTE/wsDTEConsRecCont.sh?';
    const PlazoDiasRespuestaSII = 8;
    const AMBIENTE_PRODUCCION = 'palena';
    const AMBIENTE_CERTIFICACION = 'maullin';
    const RCV_PRODUCCION = 'https://www4.sii.cl/consdcvinternetui/services/data/facadeService/';
    const RCV_CERTIFICACION = 'https://www4c.sii.cl/consdcvinternetui/services/data/facadeService/';
    const URL_SOLICITA_FOLIOS_PRODUCCION = "https://palena.sii.cl/cvc_cgi/dte/of_solicita_folios";
    const URL_SOLICITA_FOLIOS_CERTIFICACION = "https://maullin.sii.cl/cvc_cgi/dte/of_solicita_folios";
    const URL_SOLICITA_FOLIOS_DCTO_PRODUCCION = "https://palena.sii.cl/cvc_cgi/dte/of_solicita_folios_dcto";
    const URL_SOLICITA_FOLIOS_DCTO_CERTIFICACION = "https://maullin.sii.cl/cvc_cgi/dte/of_solicita_folios_dcto";
    const URL_CONFIRMA_FOLIO_PRODUCCION = "https://palena.sii.cl/cvc_cgi/dte/of_confirma_folio";
    const URL_CONFIRMA_FOLIO_CERTIFICACION = "https://maullin.sii.cl/cvc_cgi/dte/of_confirma_folio";
    const URL_GENERA_FOLIO_PRODUCCION = "https://palena.sii.cl/cvc_cgi/dte/of_genera_folio";
    const URL_GENERA_FOLIO_CERTIFICACION ="https://maullin.sii.cl/cvc_cgi/dte/of_genera_folio";
    const URL_GENERA_ARCHIVO_PRODUCCION = "https://palena.sii.cl/cvc_cgi/dte/of_genera_archivo";
    const URL_GENERA_ARCHIVO_CERTIFICACION ="https://maullin.sii.cl/cvc_cgi/dte/of_genera_archivo";

    /** @var Empresa */
    private $empresa;

    private $ambiente;

    private $runningInConsole;

    private $reintentos = 5;

    private static $direcciones_regionales = [
        'ARICA' => 'ARICA',
        'CAMARONES' => 'ARICA',
        'PUTRE' => 'ARICA',
        'GENERAL LAGOS' => 'ARICA',
        'IQUIQUE' => 'IQUIQUE',
        'PICA' => 'IQUIQUE',
        'POZO ALMONTE' => 'IQUIQUE',
        'HUARA' => 'IQUIQUE',
        'CAMIÑA' => 'IQUIQUE',
        'COLCHANE' => 'IQUIQUE',
        'ALTO HOSPICIO' => 'IQUIQUE',
        'ANTOFAGASTA' => 'ANTOFAGASTA',
        'MEJILLONES' => 'ANTOFAGASTA',
        'SIERRA GORDA' => 'ANTOFAGASTA',
        'CALAMA' => 'CALAMA',
        'SAN PEDRO DE ATACAMA' => 'CALAMA',
        'OLLAGUE' => 'CALAMA',
        'TOCOPILLA' => 'TOCOPILLA',
        'MARÍA ELENA' => 'TOCOPILLA',
        'TALTAL' => 'TALTAL',
        'COPIAPÓ' => 'COPIAPÓ',
        'CALDERA' => 'COPIAPÓ',
        'TIERRA AMARILLA' => 'COPIAPÓ',
        'CHAÑARAL' => 'CHAÑARAL',
        'DIEGO DE ALMAGRO' => 'CHAÑARAL',
        'VALLENAR' => 'VALLENAR',
        'FREIRINA' => 'VALLENAR',
        'HUASCO' => 'VALLENAR',
        'ALTO DEL CARMEN' => 'VALLENAR',
        'LA SERENA' => 'LA SERENA',
        'LA HIGUERA' => 'LA SERENA',
        'PAIHUANO' => 'LA SERENA',
        'ANDACOLLO' => 'LA SERENA',
        'VICUÑA' => 'LA SERENA',
        'OVALLE' => 'OVALLE',
        'MONTE PATRIA' => 'OVALLE',
        'PUNITAQUI' => 'OVALLE',
        'COMBARBALÁ' => 'OVALLE',
        'RÍO HURTADO' => 'OVALLE',
        'ILLAPEL' => 'ILLAPEL',
        'SALAMANCA' => 'ILLAPEL',
        'LOS VILOS' => 'ILLAPEL',
        'CANELA' => 'ILLAPEL',
        'COMQUIMBO' => 'COQUIMBO',
        'VALPARAÍSO' => 'VALPARAÍSO',
        'CASABLANCA' => 'VALPARAÍSO',
        'JUAN FERNÁNDEZ' => 'VALPARAÍSO',
        'ISLA DE PASCUA' => 'VALPARAÍSO',
        'CONCÓN' => 'VIÑA DEL MAR',
        'QUINTERO' => 'VIÑA DEL MAR',
        'PUCHUNCAVÍ' => 'VIÑA DEL MAR',
        'VIÑA DEL MAR' => 'VIÑA DEL MAR',
        'LA LIGUA' => 'LA LIGUA',
        'PETORCA' => 'LA LIGUA',
        'CABILDO' => 'LA LIGUA',
        'ZAPALLAR' => 'LA LIGUA',
        'PAPUDO' => 'LA LIGUA',
        'SAN ANTONIO' => 'SAN ANTONIO',
        'SANTO DOMINGO' => 'SAN ANTONIO',
        'CARTAGENA' => 'SAN ANTONIO',
        'EL TABO' => 'SAN ANTONIO',
        'EL QUISCO' => 'SAN ANTONIO',
        'ALGARROBO' => 'SAN ANTONIO',
        'QUILLOTA' => 'QUILLOTA',
        'NOGALES' => 'QUILLOTA',
        'HIJUELAS' => 'QUILLOTA',
        'LA CALERA' => 'QUILLOTA',
        'LA CRUZ' => 'QUILLOTA',
        'LIMACHE' => 'QUILLOTA',
        'OLMUÉ' => 'QUILLOTA',
        'SAN FELIPE' => 'SAN FELIPE',
        'PANQUEHUE' => 'SAN FELIPE',
        'CATEMU' => 'SAN FELIPE',
        'PUTAENDO' => 'SAN FELIPE',
        'SANTA MARÍA' => 'SAN FELIPE',
        'LLAY LLAY' => 'SAN FELIPE',
        'LOS ANDES' => 'LOS ANDES',
        'CALLE LARGA' => 'LOS ANDES',
        'SAN ESTEBAN' => 'LOS ANDES',
        'RINCONADA' => 'LOS ANDES',
        'VILLA ALEMANA' => 'VILLA ALEMANA',
        'QUILPUÉ' => 'VILLA ALEMANA',
        'RANCAGUA' => 'RANCAGUA',
        'MACHALÍ' => 'RANCAGUA',
        'GRANEROS' => 'RANCAGUA',
        'SAN FRANCISCO DE MOSTAZAL' => 'RANCAGUA',
        'DOÑIHUE' => 'RANCAGUA',
        'CODEGUA' => 'RANCAGUA',
        'RENGO' => 'RANCAGUA',
        'COLTAUCO' => 'RANCAGUA',
        'REQUINOA' => 'RANCAGUA',
        'OLIVAR' => 'RANCAGUA',
        'MALLOA' => 'RANCAGUA',
        'COINCO' => 'RANCAGUA',
        'QUINTA DE TILCOCO' => 'RANCAGUA',
        'SAN FERNANDO' => 'SAN FERNANDO',
        'CHIMBARONGO' => 'SAN FERNANDO',
        'NANCAGUA' => 'SAN FERNANDO',
        'PLACILLA' => 'SAN FERNANDO',
        'SANTA CRUZ' => 'SANTA CRUZ',
        'LOLOL' => 'SANTA CRUZ',
        'PALMILLA' => 'SANTA CRUZ',
        'PERALILLO' => 'SANTA CRUZ',
        'CHÉPICA' => 'SANTA CRUZ',
        'PUMANQUE' => 'SANTA CRUZ',
        'SAN VICENTE' => 'SAN VICENTE TAGUA TAGUA',
        'LAS CABRAS' => 'SAN VICENTE TAGUA TAGUA',
        'PEUMO' => 'SAN VICENTE TAGUA TAGUA',
        'PICHIDEGUA' => 'SAN VICENTE TAGUA TAGUA',
        'PICHILEMU' => 'PICHILEMU',
        'PAREDONES' => 'PICHILEMU',
        'MARCHIGUE' => 'PICHILEMU',
        'LITUECHE' => 'PICHILEMU',
        'LA ESTRELLA' => 'PICHILEMU',
        'TALCA' => 'TALCA',
        'SAN CLEMENTE' => 'TALCA',
        'PELARCO' => 'TALCA',
        'RÍO CLARO' => 'TALCA',
        'PENCAHUE' => 'TALCA',
        'MAULE' => 'TALCA',
        'CUREPTO' => 'TALCA',
        'SAN JAVIER' => 'TALCA',
        'LINARES' => 'LINARES',
        'YERBAS BUENAS' => 'LINARES',
        'COLBÚN' => 'LINARES',
        'LONGAVÍ' => 'LINARES',
        'VILLA ALEGRE' => 'LINARES',
        'CONSTITUCIÓN' => 'CONSTITUCIÓN',
        'EMPEDRADO' => 'CONSTITUCIÓN',
        'CAUQUENES' => 'CAUQUENES',
        'PELLUHUE' => 'CAUQUENES',
        'CHANCO' => 'CAUQUENES',
        'PARRAL' => 'PARRAL',
        'RETIRO' => 'PARRAL',
        'CURICÓ' => 'CURICÓ',
        'TENO' => 'CURICÓ',
        'ROMERAL' => 'CURICÓ',
        'MOLINA' => 'CURICÓ',
        'HUALAÑE' => 'CURICÓ',
        'SAGRADA FAMILIA' => 'CURICÓ',
        'LICANTÉN' => 'CURICÓ',
        'VICHUQUÉN' => 'CURICÓ',
        'RAUCO' => 'CURICÓ',
        'CONCEPCIÓN' => 'CONCEPCIÓN',
        'CHIGUAYANTE' => 'CONCEPCIÓN',
        'SAN PEDRO DE LA PAZ' => 'CONCEPCIÓN',
        'PENCO' => 'CONCEPCIÓN',
        'HUALQUI' => 'CONCEPCIÓN',
        'FLORIDA' => 'CONCEPCIÓN',
        'TOMÉ' => 'CONCEPCIÓN',
        'CORONEL' => 'CONCEPCIÓN',
        'LOTA' => 'CONCEPCIÓN',
        'SANTA JUANA' => 'CONCEPCIÓN',
        'ARAUCO' => 'CONCEPCIÓN',
        'CHILLÁN' => 'CHILLÁN',
        'PINTO' => 'CHILLÁN',
        'EL CARMEN' => 'CHILLÁN',
        'SAN IGNACIO' => 'CHILLÁN',
        'PEMUCO' => 'CHILLÁN',
        'YUNGAY' => 'CHILLÁN',
        'BULNES' => 'CHILLÁN',
        'QUILLÓN' => 'CHILLÁN',
        'RANQUIL' => 'CHILLÁN',
        'PORTEZUELO' => 'CHILLÁN',
        'COELEMU' => 'CHILLÁN',
        'TREHUACO' => 'CHILLÁN',
        'QUIRIHUE' => 'CHILLÁN',
        'COBQUECURA' => 'CHILLÁN',
        'NINHUE' => 'CHILLÁN',
        'CHILLÁN VIEJO' => 'CHILLÁN',
        'LOS ÁNGELES' => 'LOS ÁNGELES',
        'SANTA BARBARA' => 'LOS ÁNGELES',
        'LAJA' => 'LOS ÁNGELES',
        'QUILLECO' => 'LOS ÁNGELES',
        'NACIMIENTO' => 'LOS ÁNGELES',
        'NEGRETE' => 'LOS ÁNGELES',
        'MULCHÉN' => 'LOS ÁNGELES',
        'QUILACO' => 'LOS ÁNGELES',
        'YUMBEL' => 'LOS ÁNGELES',
        'CABRERO' => 'LOS ÁNGELES',
        'SAN ROSENDO' => 'LOS ÁNGELES',
        'TUCAPEL' => 'LOS ÁNGELES',
        'ANTUCO' => 'LOS ÁNGELES',
        'ALTO BÍO-BÍO' => 'LOS ÁNGELES',
        'SAN CARLOS' => 'SAN CARLOS',
        'SAN GREGORIO DE ÑINQUEN' => 'SAN CARLOS',
        'SAN NICOLÁS' => 'SAN CARLOS',
        'SAN FABIÁN DE ALICO' => 'SAN CARLOS',
        'TALCAHUANO' => 'TALCAHUANO',
        'HUALPÉN' => 'TALCAHUANO',
        'LEBU' => 'LEBU',
        'CURANILAHUE' => 'LEBU',
        'LOS ALAMOS' => 'LEBU',
        'CAÑETE' => 'LEBU',
        'CONTULMO' => 'LEBU',
        'TIRÚA' => 'LEBU',
        'TEMUCO' => 'TEMUCO',
        'VILCÚN' => 'TEMUCO',
        'FREIRE' => 'TEMUCO',
        'CUNCO' => 'TEMUCO',
        'LAUTARO' => 'TEMUCO',
        'PERQUENCO' => 'TEMUCO',
        'GALVARINO' => 'TEMUCO',
        'NUEVA IMPERIAL' => 'TEMUCO',
        'CARAHUE' => 'TEMUCO',
        'PUERTO SAAVEDRA' => 'TEMUCO',
        'PITRUFQUÉN' => 'TEMUCO',
        'GORBEA' => 'TEMUCO',
        'TOLTÉN' => 'TEMUCO',
        'LONCOCHE' => 'TEMUCO',
        'MELIPEUCO' => 'TEMUCO',
        'TEODORO SCHMIDT' => 'TEMUCO',
        'PADRE LAS CASAS' => 'TEMUCO',
        'CHOLCHOL' => 'TEMUCO',
        'ANGOL' => 'ANGOL',
        'PURÉN' => 'ANGOL',
        'LOS SAUCES' => 'ANGOL',
        'REINACO' => 'ANGOL',
        'COLLIPULLI' => 'ANGOL',
        'ERCILLA' => 'ANGOL',
        'VICTORIA' => 'VICTORIA',
        'TRAIGUÉN' => 'VICTORIA',
        'LUMACO' => 'VICTORIA',
        'CURACAUTÍN' => 'VICTORIA',
        'LONQUIMAY' => 'VICTORIA',
        'VILLARRICA' => 'VILLARRICA',
        'PUCÓN' => 'VILLARRICA',
        'CURARREHUE' => 'VILLARRICA',
        'VALDIVIA' => 'VALDIVIA',
        'MARIQUINA' => 'VALDIVIA',
        'LANCO' => 'LANCO',
        'MÁFIL' => 'VALDIVIA',
        'CORRAL' => 'VALDIVIA',
        'LOS LAGOS' => 'VALDIVIA',
        'PAILLACO' => 'VALDIVIA',
        'PANGUIPULLI' => 'PANGUIPULLI',
        'LA UNIÓN' => 'LA UNIÓN',
        'FUTRONO' => 'VALDIVIA',
        'RÍO BUENO' => 'LA UNIÓN',
        'LAGO RANCO' => 'LA UNIÓN',
        'PUERTO MONTT' => 'PUERTO MONTT',
        'CALBUCO' => 'PUERTO MONTT',
        'MAULLÍN' => 'PUERTO MONTT',
        'LOS MUERMOS' => 'PUERTO MONTT',
        'HUALAIHUÉ' => 'PUERTO MONTT',
        'PUERTO VARAS' => 'PUERTO VARAS',
        'COCHAMÓ' => 'PUERTO VARAS',
        'FRESIA' => 'PUERTO VARAS',
        'LLANQUIHUE' => 'PUERTO VARAS',
        'FRUTILLAR' => 'PUERTO VARAS',
        'ANCUD' => 'ANCUD',
        'QUEMCHI' => 'ANCUD',
        'OSORNO' => 'OSORNO',
        'PUYEHUE' => 'OSORNO',
        'PURRANQUE' => 'OSORNO',
        'RÍO NEGRO' => 'OSORNO',
        'SAN PABLO' => 'OSORNO',
        'SAN JUAN DE LA COSTA' => 'OSORNO',
        'PUERTO OCTAY' => 'OSORNO',
        'CASTRO' => 'CASTRO',
        'CURACO DE VÉLEZ' => 'CASTRO',
        'CHOCHI' => 'CASTRO',
        'DALCAHUE' => 'CASTRO',
        'PUQUELDÓN' => 'CASTRO',
        'QUEILÉN' => 'CASTRO',
        'QUELLÓN' => 'CASTRO',
        'CHAITÉN' => 'CHAITÉN',
        'PALENA' => 'CHAITÉN',
        'FUTALEUFÚ' => 'CHAITÉN',
        'COYHAIQUE' => 'COYHAIQUE',
        'RÍO IBAÑEZ' => 'COYHAIQUE',
        'O`HIGGINS' => 'COCHRANE',
        'TORTEL' => 'COCHRANE',
        'AYSÉN' => 'AYSÉN',
        'CISNES' => 'AYSÉN',
        'LAGO VERDE' => 'AYSÉN',
        'GUAITECAS' => 'AYSÉN',
        'CHILE CHICO' => 'CHILE CHICO',
        'COCHRANE' => 'COCHRANE',
        'GUADAL' => 'COCHRANE',
        'PUERTO BELTRAND' => 'COCHRANE',
        'PUNTA ARENAS' => 'PUNTA ARENAS',
        'RÍO VERDE' => 'PUNTA ARENAS',
        'SAN GREGORIO' => 'PUNTA ARENAS',
        'LAGUNA BLANCA' => 'PUNTA ARENAS',
        'CABO DE HORNOS' => 'PUNTA ARENAS',
        'PUERTO NATALES' => 'PUERTO NATALES',
        'TORRES DEL PAINE' => 'PUERTO NATALES',
        'PORVENIR' => 'PORVENIR',
        'PRIMAVERA' => 'PORVENIR',
        'TIMAUKEL' => 'PORVENIR',
        'INDEPENDENCIA' => 'SANTIAGO NORTE',
        'RECOLETA' => 'SANTIAGO NORTE',
        'HUECHURABA' => 'SANTIAGO NORTE',
        'CONCHALÍ' => 'SANTIAGO NORTE',
        'QUILICURA' => 'SANTIAGO NORTE',
        'COLINA' => 'SANTIAGO NORTE',
        'LAMPA' => 'SANTIAGO NORTE',
        'TILTIL' => 'SANTIAGO NORTE',
        'SANTIAGO' => 'SANTIAGO CENTRO',
        'CERRO NAVIA' => 'SANTIAGO PONIENTE',
        'CURACAVÍ' => 'SANTIAGO PONIENTE',
        'ESTACIÓN CENTRAL' => 'SANTIAGO PONIENTE',
        'LO PRADO' => 'SANTIAGO PONIENTE',
        'PUDAHUEL' => 'SANTIAGO PONIENTE',
        'QUINTA NORMAL' => 'SANTIAGO PONIENTE',
        'RENCA' => 'SANTIAGO PONIENTE',
        'MELIPILLA' => 'MELIPILLA',
        'SAN PEDRO' => 'MELIPILLA',
        'ALHUÉ' => 'MELIPILLA',
        'MARÍA PINTO' => 'MELIPILLA',
        'MAIPÚ' => 'MAIPÚ',
        'CERRILLOS' => 'MAIPÚ',
        'PADRE HURTADO' => 'MAIPÚ',
        'PEÑAFLOR' => 'MAIPÚ',
        'TALAGANTE' => 'MAIPÚ',
        'EL MONTE' => 'MAIPÚ',
        'ISLA DE MAIPO' => 'MAIPÚ',
        'LAS CONDES' => 'SANTIAGO ORIENTE',
        'VITACURA' => 'SANTIAGO ORIENTE',
        'LO BARNECHEA' => 'SANTIAGO ORIENTE',
        'ÑUÑOA' => 'ÑUÑOA',
        'LA REINA' => 'ÑUÑOA',
        'MACUL' => 'ÑUÑOA',
        'PEÑALOLÉN' => 'ÑUÑOA',
        'PROVIDENCIA' => 'PROVIDENCIA',
        'SAN MIGUEL' => 'SANTIAGO SUR',
        'LA CISTERNA' => 'SANTIAGO SUR',
        'SAN JOAQUÍN' => 'SANTIAGO SUR',
        'PEDRO AGUIRRE CERDA' => 'SANTIAGO SUR',
        'LO ESPEJO' => 'SANTIAGO SUR',
        'LA GRANJA' => 'SANTIAGO SUR',
        'LA PINTANA' => 'SANTIAGO SUR',
        'SAN RAMÓN' => 'SANTIAGO SUR',
        'LA FLORIDA' => 'LA FLORIDA',
        'PUENTE ALTO' => 'LA FLORIDA',
        'PIRQUE' => 'LA FLORIDA',
        'SAN JOSÉ DE MAIPO' => 'LA FLORIDA',
        'SAN BERNARDO' => 'SAN BERNARDO',
        'CALERA DE TANGO' => 'SAN BERNARDO',
        'EL BOSQUE' => 'SAN BERNARDO',
        'BUIN' => 'BUIN',
        'PAINE' => 'BUIN',
    ];

    public function __construct(Empresa $empresa = null)
    {
        if($empresa !== null){
            $this->empresa = $empresa;
        }

        $this->runningInConsole = app()->runningInConsole();
        $this->ambiente = config('dte.environment');
    }

    public function setEmpresa(Empresa $empresa)
    {
        $this->empresa = $empresa;
    }

    public function setAmbiente($ambiente)
    {
        $this->ambiente = $ambiente;
    }

    public function empresaEncontrada()
    {
        if (empty($this->empresa)) {
            return false;
        }

        return true;
    }

    public function throwExceptionEmpresaNoEncontrada()
    {
        if ($this->runningInConsole) {
            echo 'empresa no encontrada';
            Log::info('La empresa no fue encontrada');
            exit();
        } else {
            throw new HttpResponseException(response()->json([
                'message' => '422 error',
                'errors' => ['empresa_id'=>['La empresa no fue encontrada']],
                'status_code' => 422,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }
    }

    public function throwException($mensaje)
    {
        if ($this->runningInConsole) {
            echo $mensaje."\n";
            Log::error($mensaje);
        //exit();
        } else {
            throw new HttpResponseException(response()->json([
                'message' => '422 error',
                'errors' => ['mensaje'=>[$mensaje]],
                'status_code' => 422,
            ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
        }
    }

    public function firmarSemilla(string $semilla): \DOMDocument
    {
        $body = "<getToken><item><Semilla>$semilla</Semilla></item></getToken>";
        $dom = new \DOMDocument;
        $dom->formatOutput = false;
        $dom->preserveWhiteSpace = true;
        $dom->loadXML($body);
        $xmlTool = new \FR3D\XmlDSig\Adapter\XmlseclibsAdapter();
        $certificado = $this->empresa->certificados()->where('enUso', 1)->first();
        $pfx = Storage::cloud()->get($certificado->originalFile->file_path);
        $key = [];
        openssl_pkcs12_read($pfx, $key, $certificado->password);
        $xmlTool->setPrivateKey($key['pkey']);
        $xmlTool->setpublickey($key['cert']);
        $xmlTool->addTransform(\FR3D\XmlDSig\Adapter\XmlseclibsAdapter::ENVELOPED);
        $xmlTool->sign($dom);

        return $dom;
    }

    public function obtenerToken($boleta = 0)
    {
        $semilla = $this->obtenerSemilla($boleta);

        if (! $semilla) {
            return false;
        }

        $dom = $this->firmarSemilla($semilla);

        if($boleta == 0){
            $wsdl_token = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::TokenProduccion : self::TokenCertificacion;
            $body_token = null;
            for ($i = 0; $i < $this->reintentos; $i++) {
                try {
                    $tokenClient = new \SoapClient($wsdl_token, []);
                    $body_token = $tokenClient->__soapCall('getToken', [$dom->saveXML()]);
                    break;
                } catch (\Throwable  $e) {
                    Log::error($e->getMessage());
                    $body_token = null;
                    usleep(200000);
                }
            }
        }else{
            $client = new \GuzzleHttp\Client();
            $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::TokenBoletaProduccion : self::TokenBoletaCertificacion;
            $response = $client->post($url,
                [
                    'body' => $dom->saveXML(),
                    'headers' => [
                        'Content-type' => 'application/xml',
                        'User-Agent' => self::USER_AGENT,
                        'Accept' => 'application/xml',
                    ]
                ]);
            $body_token = $response->getBody()->getContents();
        }


        if ($body_token === null) {
            Log::error('Existio un error al intentar conectar con el SII - Token');
            return false;
        }

        $formato = str_replace('SII:', '', $body_token);
        $xml = simplexml_load_string($formato);
        $token = (string) $xml->RESP_BODY->TOKEN;
        return $token;
    }

    public function obtenerSemilla($boleta = 0)
    {
        /* @var EmpresaParametro $parametro */
        /* @var CertificadoEmpresa $certificado */

        if (! self::empresaEncontrada()) {
            self::throwExceptionEmpresaNoEncontrada();
        }

        if($boleta == 0){
            $wsdl_semilla = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::SemillaProduccion : self::SemillaCertificacion;

            for ($i = 0; $i < $this->reintentos; $i++) {
                try {
                    $soap_client = new \SoapClient($wsdl_semilla, []);
                    $body = $soap_client->__soapCall('getSeed', []);

                    break;
                } catch (\Throwable  $e) {
                    Log::error($e->getMessage());
                    $body = null;
                    usleep(200000);
                }
            }

            if ($body === null) {
                Log::error('Existio un error al intentar conectar con el SII - Semilla');

                return false;
            }
        }else{
            $client = new \GuzzleHttp\Client();
            $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::SemillaBoletaProduccion : self::SemillaBoletaCertificacion;

            $response = $client->get($url,
                [
                    'headers' => [
                        'User-Agent' => self::USER_AGENT,
                        'Accept' => 'application/xml',
                    ]
                ]);
            $body = $response->getBody()->getContents();
        }



        $seedXML = new \DOMDocument;
        $seedXML->loadXML($body);
        $semilla = (string) $seedXML->getElementsByTagName('SEMILLA')->item(0)->nodeValue;
        $estado = $seedXML->getElementsByTagName('ESTADO')->item(0)->nodeValue;

        if ($estado !== '00') {
            Log::error('Error al obtener semilla estado: '.$estado);

            return false;
        }

        return $semilla;
    }

    public function obtenerCookies($path_cert, $password_cert): CookieJar
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get('https://hercules.sii.cl/cgi_AUT2000/autInicio.cgi?referencia=https://misiir.sii.cl/cgi_misii/siihome.cgi',
                ['headers' => [
                    'User-Agent' => self::USER_AGENT,
                    'Accept' => '*/*',
                    'Connection' => 'keep-alive',
                ],
                    'curl' => [
                        CURLOPT_RETURNTRANSFER => 'true',
                        CURLOPT_SSLCERT => $path_cert,
                        CURLOPT_SSLCERTPASSWD => $password_cert,
                    ],
                ]);
        } catch (ConnectException $e) {
            $this->throwException($e->getMessage());
        }

        $cookies = $response->getHeader('Set-Cookie');
        $cookies_array = [];
        //$cookies_str = "";
        foreach ($cookies as $item) {
            $cookie = explode('=', $item);
            $cookie_value = explode(';', $cookie[1]);
            //$cookies_str .= $cookie[0] . "=" . $cookie_value[0] . "; ";
            $cookies_array[$cookie[0]] = $cookie_value[0];
        }

        $cookieJar = CookieJar::fromArray($cookies_array, 'sii.cl');

        return $cookieJar;
    }

    public static function getRutArray($rut)
    {

        try {
            return [
                'number' => Rut::parse($rut)->number(),
                'dv' => Rut::parse($rut)->vn(),
                'format_complete' => Rut::parse($rut)->format(Rut::FORMAT_COMPLETE)
            ];
        } catch (InvalidFormatException $e) {
        }
    }

    public function obtenerCookiesNoCERT($rut, $password): CookieJar
    {
        try {
            $rut_array = self::getRutArray($rut);
            $client = new \GuzzleHttp\Client();
            $response = $client->post('https://zeusr.sii.cl/cgi_AUT2000/CAutInicio.cgi',
                [
                    'headers' => [
                        'User-Agent' => self::USER_AGENT,
                        'Accept' => '*/*',
                        'Connection' => 'keep-alive',
                    ],
                    'curl' => [
                        CURLOPT_RETURNTRANSFER => 'true',
                    ],
                    'form_params' => [
                        'rutcntr' => $rut_array['format_complete'],
                        'clave' => $password,
                        'rut' => $rut_array['number'],
                        'dv' => $rut_array['dv'],
                        'referencia' => 'https://misiir.sii.cl/cgi_misii/siihome.cgi'
                    ]
                ]);
        } catch (ConnectException $e) {
            $this->throwException($e->getMessage());
        }
        $cookies = $response->getHeader('Set-Cookie');
        $cookies_array = [];
        //$cookies_str = "";
        foreach ($cookies as $item) {
            $cookie = explode('=', $item);
            $cookie_value = explode(';', $cookie[1]);
            //$cookies_str .= $cookie[0] . "=" . $cookie_value[0] . "; ";
            $cookies_array[$cookie[0]] = $cookie_value[0];
        }

        $cookieJar = CookieJar::fromArray($cookies_array, 'sii.cl');

        return $cookieJar;
    }

    public function descargarListadoContribuyentes(string $path_csv, string $path_certificado, string $password_certificado)
    {
        $client = new \GuzzleHttp\Client();
        $resource = fopen($path_csv, 'w');
        $cookieJar = $this->obtenerCookies($path_certificado, $password_certificado);

        $response = $client->get(self::generarUrlDescargaContribuyentes(),
            [
                'headers' => [
                    'User-Agent' => self::USER_AGENT,
                    'Accept' => '*/*',
                ],
                'sink' => $resource,
                'cookies' => $cookieJar,
                'connect_timeout' => 5,
            ]);
    }

    public static function generarUrlDescargaContribuyentes()
    {
        if (date('H') > 0 && date('H') < 13) {
            $sol = (strtotime(date('Y-m-d')) - 3600);
        } else {
            $sol = strtotime(date('Y-m-d'));
        }

        $date = date('Y', $sol).date('m', $sol).date('d', $sol);
        $url = 'https://palena.sii.cl/cvc_cgi/dte/ce_empresas_dwnld?NOMBRE_ARCHIVO=ce_empresas_dwnld_'.$date.'.csv';

        return $url;
    }

    public static function getDireccionRegional($comuna)
    {
        if (! $comuna) {
            return 'DESCONOCIDA';
        }

        if (! is_numeric($comuna)) {
            $direccion = mb_strtoupper($comuna, 'UTF-8');

            return isset(self::$direcciones_regionales[$direccion]) ? self::$direcciones_regionales[$direccion] : $direccion;
        }

        return 'SUC '.$comuna;
    }

    public function multipart_build_query($fields, $boundary, $xml_string = '', $xml_name = '')
    {
        $retval = '';

        foreach ($fields as $key => $value) {
            if ($key == 'archivo') {
                $retval .= "$boundary\r\nContent-Disposition: form-data; name=\"$key\"; filename=\"$xml_name\"\r\n";
                $retval .= "Content-Type: text/xml\r\n\r\n";
                $dom4 = new \DOMDocument;
                $dom4->formatOutput = false;
                $dom4->preserveWhiteSpace = true;
                $dom4->loadXML($xml_string);
                $retval .= $dom4->saveXML()."\r\n\r\n";
            } else {
                $retval .= "$boundary\r\nContent-Disposition: form-data; name=\"$key\"\r\n\r\n$value\r\n";
            }
        }
        $retval .= "$boundary--\r\n";

        return $retval;
    }

    public function listHistoryDocEvents($data)
    {
        $TokenSII = false;
        for ($i = 0; $i < $this->reintentos; $i++) {
            $TokenSII = $this->obtenerToken();
            if ($TokenSII !== false) {
                break;
            }
        }

        if ($TokenSII === false) {
            return false;
        }

        $rut_array = self::splitRutOnTwo($data['rut']);
        $data_to_send = [
            'rutEmisor' => $rut_array['rutEmisor'],
            'dvEmisor' => $rut_array['dvEmisor'],
            'tipoDoc' =>(string) $data['doc_type'],
            'folio' => (string) $data['folio'],
            ];
        $response = null;
        $wsdl_acceptance_claims = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::WSRegistroReclamoProduccion : self::WSRegistroReclamoCertificacion;
        for ($i = 0; $i < $this->reintentos; $i++) {
            try {
                $historyClient = new \SoapClient($wsdl_acceptance_claims, []);
                $historyClient->__setCookie('TOKEN', $TokenSII);
                $response = $historyClient->__soapCall('listarEventosHistDoc', $data_to_send);

                break;
            } catch (\Throwable  $e) {
                Log::error($e->getMessage());
                $response = null;
                usleep(200000);
            }
        }

        if ($response === null) {
            Log::error('Existio un error al intentar conectar con el SII - listHistoryDocEvents');

            return false;
        }

        return $response;
    }

    public function getDocumentReceptionDate($data)
    {
        $TokenSII = false;
        for ($i = 0; $i < $this->reintentos; $i++) {
            $TokenSII = $this->obtenerToken();
            if ($TokenSII !== false) {
                break;
            }
        }

        if ($TokenSII === false) {
            return false;
        }

        $data_to_send = [
            'rutEmisor' => $data['rutEmisor'],
            'dvEmisor' => $data['dvEmisor'],
            'tipoDoc' =>(string) $data['tipoDoc'],
            'folio' => (string) $data['folio'],
        ];

        $response = null;
        $wsdl_acceptance_claims = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::WSRegistroReclamoProduccion : self::WSRegistroReclamoCertificacion;
        for ($i = 0; $i < $this->reintentos; $i++) {
            try {
                $historyClient = new \SoapClient($wsdl_acceptance_claims, []);
                $historyClient->__setCookie('TOKEN', $TokenSII);
                $response = $historyClient->__soapCall('consultarFechaRecepcionSii', $data_to_send);

                break;
            } catch (\Throwable  $e) {
                Log::error($e->getMessage());
                $response = null;
                usleep(200000);
            }
        }

        if ($response === null) {
            Log::error('Existio un error al intentar conectar con el SII - getDocumentReceptionDate');

            return false;
        }

        return $response;
    }

    public function documentActionToSii($data)
    {
        $TokenSII = false;
        for ($i = 0; $i < $this->reintentos; $i++) {
            $TokenSII = $this->obtenerToken();
            if ($TokenSII !== false) {
                break;
            }
        }

        if ($TokenSII === false) {
            return false;
        }

        $rut_array = self::splitRutOnTwo($data['rut']);
        $data_ws = array_merge($data, $rut_array);
        unset($data_ws['rut']);
        unset($data_ws['company_id']);

        $response = null;
        $wsdl_acceptance_claims = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::WSRegistroReclamoProduccion : self::WSRegistroReclamoCertificacion;
        for ($i = 0; $i < $this->reintentos; $i++) {
            try {
                $historyClient = new \SoapClient($wsdl_acceptance_claims, []);
                $historyClient->__setCookie('TOKEN', $TokenSII);
                $response = $historyClient->__soapCall('ingresarAceptacionReclamoDoc', $data_ws);

                break;
            } catch (\Throwable  $e) {
                Log::error($e->getMessage());
                $response = null;
                usleep(200000);
            }
        }

        if ($response === null) {
            Log::error('Existio un error al intentar conectar con el SII - documentActionToSii');

            return false;
        }

        return $response;
    }

    public static function splitRutOnTwo($rut)
    {
        $pRutEmisor = substr($rut, 0, -2);
        $pDigEmisor = substr($rut, -1);
        $data_ws = [];
        $data_ws['rutEmisor'] = $pRutEmisor;
        $data_ws['dvEmisor'] = $pDigEmisor;

        return $data_ws;
    }

    public function subirRcfAlSii(FolioConsumption $folioConsumption, File $file = null){

        $pRutEnvia = substr($folioConsumption->rutEnvia, 0, -2);
        $pDigEnvia = substr($folioConsumption->rutEnvia, -1);
        $pRutEmpresa = substr($folioConsumption->rutEmisor, 0, -2);
        $pDigEmpresa = substr($folioConsumption->rutEmisor, -1);

        if($file === null){
            $file = $folioConsumption->files()->latest()->first();
        }

        $xml_string = Storage::cloud()->get($file->file_path);
        $data = ['rutSender'=>$pRutEnvia, 'dvSender'=>$pDigEnvia, 'rutCompany'=>$pRutEmpresa, 'dvCompany'=>$pDigEmpresa, 'archivo'=>$file->file_name];
        $agent = 'Mozilla/5.0 (compatible; PROG 1.0; Windows NT 5.0; YComp 5.0.2.4)';
        $boundary = '--7d23e2a11301c4';
        $cuerpo = $this->multipart_build_query($data, $boundary, $xml_string, $file->file_name);

        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::UploadProduccion : self::UploadCertificacion;

        /* @var $file File*/
        for ($i = 0; $i < $this->reintentos; $i++) {
            $tokenSII = $this->obtenerToken();
            if ($tokenSII !== false) {
                break;
            }
        }

        if ($tokenSII === false) {
            return false;
        }

        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::UploadProduccion : self::UploadCertificacion;
        $headers =
            [
                'POST /cgi_dte/UPL/DTEUpload HTTP/1.0',
                'Accept: */*',
                'Content-Type:multipart/form-data: boundary=7d23e2a11301c4',
                "User-Agent: $agent",
                "Cookie: TOKEN=$tokenSII",
            ];

        try {
            for ($i = 0; $i < $this->reintentos; $i++) {
                $client = new \GuzzleHttp\Client();
                $response = $client->post($url,
                    [
                        'curl' => [
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_PORT => 443,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_HTTPHEADER => $headers,
                            CURLOPT_POSTFIELDS => $cuerpo,
                        ],

                    ]);

                if ($response->getStatusCode() != 500) {
                    break;
                }
            }

            $body = $response->getBody();
            $xml = ($response and $response != 'Error 500') ? new \SimpleXMLElement($body, LIBXML_COMPACT) : false;

            $estadoUpload = (int) (string) $xml->STATUS;
            $trackId = 0;
            $error = '';

            if ($estadoUpload == 0) {
                $trackId = (int) (string) $xml->TRACKID;
            }

            if ($estadoUpload == 99){
                $error = $xml->DETAIL->ERROR;
            }

        } catch (ConnectException $e) {
            $this->throwException($e->getMessage());
        }

        return ['status' => $estadoUpload, 'trackId' => $trackId, 'error' => $error];
    }

    public function subirEnvioDteAlSii(EnvioDte $envioDte, $file_id)
    {
        /* @var $file File*/
        for ($i = 0; $i < $this->reintentos; $i++) {
            $tokenSII = $this->obtenerToken($envioDte->boleta);
            if ($tokenSII !== false) {
                break;
            }
        }

        if ($tokenSII === false) {
            return false;
        }

        $pRutEnvia = substr($envioDte->rutEnvia, 0, -2);
        $pDigEnvia = substr($envioDte->rutEnvia, -1);
        $pRutEmpresa = substr($envioDte->rutEmisor, 0, -2);
        $pDigEmpresa = substr($envioDte->rutEmisor, -1);

        $file = $envioDte->archivos()->where('file_id', $file_id)->first();
        $xml_name = $envioDte->setDteId.'.xml';

        $xml_string = Storage::cloud()->get($file->file_path);
        $data = ['rutSender'=>$pRutEnvia, 'dvSender'=>$pDigEnvia, 'rutCompany'=>$pRutEmpresa, 'dvCompany'=>$pDigEmpresa, 'archivo'=>$xml_name];
        $agent = 'Mozilla/5.0 (compatible; PROG 1.0; Windows NT 5.0; YComp 5.0.2.4)';
        $boundary = '--7d23e2a11301c4';
        $cuerpo = $this->multipart_build_query($data, $boundary, $xml_string, $xml_name);
        $cuerpo = str_replace("'", '&apos;', $cuerpo);

        if($envioDte->boleta == 0){
            $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::UploadProduccion : self::UploadCertificacion;
        }else{
            $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::UploadBoletaProduccion : self::UploadBoletaCertificacion;
        }

        $headers =
            [
                'POST /cgi_dte/UPL/DTEUpload HTTP/1.0',
                'Accept: */*',
                'Content-Type:multipart/form-data: boundary=7d23e2a11301c4',
                "User-Agent: $agent",
                "Cookie: TOKEN=$tokenSII",
            ];

        try {
            for ($i = 0; $i < $this->reintentos; $i++) {
                $client = new \GuzzleHttp\Client();
                $response = $client->post($url,
                    [
                        'curl' => [
                            CURLOPT_RETURNTRANSFER => 1,
                            CURLOPT_PORT => 443,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                            CURLOPT_HTTPHEADER => $headers,
                            CURLOPT_POSTFIELDS => $cuerpo,
                        ],

                    ]);

                if ($response->getStatusCode() != 500) {
                    break;
                }
            }

            $body = $response->getBody();

            if($envioDte->boleta == 0){
                $xml = ($response and $response != 'Error 500') ? new \SimpleXMLElement($body, LIBXML_COMPACT) : false;

                $estadoUpload = (int) (string) $xml->STATUS;
                $trackId = 0;
                $error = '';

                if ($estadoUpload == 0) {
                    $trackId = (int) (string) $xml->TRACKID;
                }

                if ($estadoUpload == 99) {
                    $error = $xml->DETAIL->ERROR;
                }
            }else{
                $content = json_decode($body->getContents());
                $trackId = (int) (string) $content->trackid;
                $estadoUpload = $content->estado == 'REC' ? 0 : 99;
                $error = 0;
            }


        } catch (ConnectException $e) {
            $this->throwException($e->getMessage());
        } catch (ClientException $clientException){
            //$this->throwException($clientException->getMessage());
            return false;
        }

        return ['status' => $estadoUpload, 'trackId' => $trackId, 'error' => $error];
    }

    public static function getRspUploadTextFromStatus($id)
    {
        switch ($id) {
            case 0:
                return 'Upload OK';

                break;

            case 1:
                return 'El Sender no tiene permiso para enviar';

                break;

            case 2:
                return 'Error en tamaño del archivo(muy grande o muy chico)';

                break;

            case 3:
                return 'Archivo cortado(tamaño <> al parámetro size';

                break;

            case 5:
                return 'No está autenticado';

                break;

            case 6:
                return 'Empresa no autorizada a enviar archivos';

                break;

            case 7:
                return 'Esquema Invalido';

                break;

            case 8:
                return 'Firma del Documento';

                break;

            case 9:
                return 'Sistema Bloqueado';

                break;
        }
    }

    public function generarUrlDescargaDtesRecibidos($rut, $desde, $hasta)
    {
        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::DTESRecibidosProduccion : self::DTESRecibidosProduccion;
        $url = $url."DESDE=$desde&HASTA=$hasta&RUT=$rut&TIPO_CONSULTA=Bajar Archivo";

        return $url;
    }

    public function generarUrlSolicitaFolios()
    {
        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::URL_SOLICITA_FOLIOS_PRODUCCION : self::URL_SOLICITA_FOLIOS_CERTIFICACION;

        return $url;
    }

    public function generarUrlSolicitaFoliosDcto()
    {
        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::URL_SOLICITA_FOLIOS_DCTO_PRODUCCION : self::URL_SOLICITA_FOLIOS_DCTO_CERTIFICACION;

        return $url;
    }

    public function generarUrlConfirmaFolio()
    {
        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::URL_CONFIRMA_FOLIO_PRODUCCION : self::URL_CONFIRMA_FOLIO_CERTIFICACION;

        return $url;
    }

    public function generarUrlGeneraFolio()
    {
        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::URL_GENERA_FOLIO_PRODUCCION : self::URL_GENERA_FOLIO_CERTIFICACION;

        return $url;
    }

    public function generarUrlGeneraArchivo()
    {
        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::URL_GENERA_ARCHIVO_PRODUCCION : self::URL_GENERA_ARCHIVO_CERTIFICACION;

        return $url;
    }

    public function recuperarDtesRecibidos($desde, $hasta)
    {
        /* @var CertificadoEmpresa $certificado */
        $certificado = $this->empresa->certificados()->where('enUso', 1)->first();

        $uniq_cert = uniqid().'.pem';
        $uniq_id = uniqid().'.csv';
        Storage::put($uniq_id, '');
        Storage::put($uniq_cert, $certificado->pemFile->content());
        $path_csv = Storage::path($uniq_id);
        $path_certificado = Storage::path($uniq_cert);

        $client = new \GuzzleHttp\Client();
        $resource = fopen($path_csv, 'w');
        $cookieJar = $this->obtenerCookies($path_certificado, $certificado->password);

        $response = $client->get($this->generarUrlDescargaDtesRecibidos($this->empresa->rut, $desde, $hasta),
            [
                'headers' => [
                    'User-Agent' => self::USER_AGENT,
                    'Accept' => '*/*',
                ],
                'sink' => $resource,
                'cookies' => $cookieJar,
                'curl' => [
                    CURLOPT_RETURNTRANSFER => false,
                    CURLOPT_BINARYTRANSFER => true,
                    CURLOPT_PORT => 443,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_SSL_VERIFYHOST => false,
                ],
            ]);

        Storage::delete($uniq_cert);

        return $uniq_id;
    }

    public function getRCVDetail($data, $type = 'CERT')
    {

        if($type == 'CERT'){
            $tokenSII = false;
            for ($i = 0; $i < $this->reintentos; $i++) {
                $tokenSII = $this->obtenerToken();
                if ($tokenSII !== false) {
                    break;
                }
            }

            if ($tokenSII === false) {
                return false;
            }
        }else{
            $cookies = $this->obtenerCookiesNoCERT($this->empresa->rut, '');
            $tokenSII = $cookies->getCookieByName('TOKEN')->getValue();
        }


        $pRutEmpresa = substr($this->empresa->rut, 0, -2);
        $pDigEmpresa = substr($this->empresa->rut, -1);

        if ($data['tipo'] == 'RESUMEN'){
            $append_url = 'getResumenExport';
        }elseif($data['tipo'] == 'DETALLE'){
            $append_url = $data['operacion'] == 'VENTA' ? 'getDetalleVentaExport' : 'getDetalleCompraExport';
        }

        $url = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::RCV_PRODUCCION : self::RCV_CERTIFICACION;
        $uuid = uniqid();
        $estadoContab = $data['estadoContab'];
        $ptributario = $data['ptributario'];
        $operacion = $data['operacion'];
        $client = new \GuzzleHttp\Client();
        $array =
            [
                'data'=> [
                    'codTipoDoc'=>0,
                    'dvEmisor' => $pDigEmpresa,
                    'estadoContab'=> $estadoContab,
                    'operacion' => $operacion,
                    'ptributario' => $ptributario,
                    'rutEmisor' => $pRutEmpresa,
                ],
                    'metaData' => [
                     'conversationId' => $tokenSII,
                     'namespace' => "cl.sii.sdi.lob.diii.consdcv.data.api.interfaces.FacadeService/$append_url",
                     'page' => null,
                     'transactionId' => $uuid,
                ],
            ];
        try {
            $request = $client->post($url.$append_url,
                [
                    'body' => json_encode($array),
                    'headers' => [
                        'User-Agent' => self::USER_AGENT,
                        'Accept' => 'application/json, text/plain, */*',
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'Accept-Language' => 'es-ES,es;q=0.9',
                        'Cookie' => "TOKEN=$tokenSII;",
                        'Content-Type' => 'application/json',
                    ],
                    'curl' => [
                        CURLOPT_RETURNTRANSFER => 'true',
                        CURLOPT_PORT => 443,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
                        CURLOPT_SSL_VERIFYPEER => false,
                        CURLOPT_SSL_VERIFYHOST => false,
                    ],
                ]);

            if ($request->getStatusCode() != 500) {
                return $request->getBody()->getContents();
            } else {
                return false;
            }
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    public function consultarEstadoDte($documento){
        $tokenSII = false;
        for ($i = 0; $i < $this->reintentos; $i++) {
            $tokenSII = $this->obtenerToken();
            if ($tokenSII !== false) {
                break;
            }
        }

        if ($tokenSII === false) {
            return false;
        }
        $array_rut_emisor = self::getRutArray($documento['rut_emisor']);
        $array_rut_receptor = self::getRutArray($documento['rut_receptor']);
        $array_rut_consultante = self::getRutArray($documento['rut_consultante']);

        $data_to_send = [
            'RutConsultante' => $array_rut_consultante['number'],
            'DvConsultante' => $array_rut_consultante['dv'],
            'RutCompania' => $array_rut_emisor['number'],
            'DvCompania' => $array_rut_emisor['dv'],
            'RutReceptor' => $array_rut_receptor['number'],
            'DvReceptor' => $array_rut_receptor['dv'],
            'TipoDte' => $documento['tipo'],
            'FolioDte' => $documento['folio'],
            'FechaEmisionDte' => $documento['fecha_emision'],
            'MontoDte' => $documento['monto'],
            'Token' => "$tokenSII"
        ];
        $response = null;
        $wsdl_consulta = ($this->ambiente == self::AMBIENTE_PRODUCCION) ? self::WSEstadoDTEProduccion : self::WSEstadoDTECertificacion;
        for ($i = 0; $i < $this->reintentos; $i++) {
            try {
                $stateClient = new \SoapClient($wsdl_consulta, []);
                $stateClient->__setCookie('TOKEN', $tokenSII);
                $response = $stateClient->__soapCall('getEstDte', $data_to_send);

                break;
            } catch (\Throwable  $e) {
                Log::error($e->getMessage());
                $response = null;
                usleep(200000);
            }
        }

        if ($response === null) {
            Log::error('Existio un error al intentar conectar con el SII - getEstDte');

            return false;
        }

        return $response;
    }
}
