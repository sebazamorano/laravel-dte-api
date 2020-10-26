<?php

namespace App\Http\Request\API;

use App\Models\TipoDocumento;
use App\Http\Request\APIRequest;
use App\Models\DocumentoAutorizado;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Validation\Validator;
use App\Models\ActividadEconomicaEmpresa;
use App\Repositories\ActividadEconomicaRepository;

class CreateBoletaAPIRequest extends APIRequest
{
    private $monto_neto = 0;

    private $monto_exento = 0;

    private $ind_exe_count = 0;

    private $tasaIVA;

    private $monto_iva_incluido = 0;

    /** @var ActividadEconomicaRepository */
    private $actividadEconomicaRepository;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

    public function __construct(ActividadEconomicaRepository $actividadEconomicaRepo)
    {
        $this->tasaIVA = 19;
        $this->actividadEconomicaRepository = $actividadEconomicaRepo;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'empresa_id' => 'required|integer|exists:empresas,id',
            'sucursal_id' => 'nullable|integer|exists:sucursales,id',
            'entidad_id' => 'nullable|integer|exists:entidades,id',
            'entidad_sucursal_id' => 'nullable|integer',
            'caf_id' => 'nullable|integer',
            'certificado_empresa_id' => 'nullable|integer',
            'observaciones' => 'nullable|string',
            'fechaEmision' => 'required|date_format:Y-m-d',
            'folio' => 'nullable|integer',
            'folioString' => 'nullable|string',
            'tipo_pago_id' => 'nullable|integer',
            'tipo_plazo_id' => 'nullable|integer',
            'fechaVencimiento' => 'nullable|date',
            'neto' => 'nullable|integer',
            'exento' => 'nullable|integer',
            'iva' => 'nullable|integer',
            'total' => 'required|integer',
            'costo' => 'nullable|integer',
            'margen' => 'nullable|integer',
            'saldo' => 'nullable|integer',
            'cancelado' => 'nullable|boolean',
            'fechaPago' => 'nullable|date',
            'nota' => 'nullable|string',
            'TSTED' => 'nullable',
            'IO' => 'integer',
            'user_id' => 'nullable|integer',
            'estado_adicional' => 'nullable|integer',
            'estadoPago' => 'nullable|integer',
            'estado' => 'integer',
            'transporte.Patente' => 'nullable|string|max:8',
            'transporte.RUTTrans' => 'nullable|cl_rut',
            'transporte.RUTChofer' => 'nullable|cl_rut|required_with:transporte.NombreChofer',
            'transporte.NombreChofer' => 'nullable|string|max:30|required_with:transporte.RUTChofer',
            'transporte.DirDest' => 'nullable|string|max:70',
            'transporte.CmnaDest' => 'nullable|string|max:20',
            'transporte.CiudadDest' => 'nullable|string|max:20',
            'totales.TpoMoneda' => 'nullable|string|max:15',
            'totales.MntNeto' => 'nullable|numeric|required_with:totales.IVA',
            'totales.MntExe' => 'nullable|numeric|required_if:TipoDTE,41',
            'totales.TasaIVA' => 'nullable|numeric|required_with:totales.IVA',
            'totales.IVA' => 'nullable|numeric|required_unless:TipoDTE,41',
            'totales.IVAProp' => 'nullable|integer',
            'totales.IVATerc' => 'nullable|integer',
            'totales.IVANoRet' => 'nullable|integer',
            'totales.MntTotal' => 'required|numeric',
            'totales.MontoNF' => 'nullable|integer',
            'totales.MontoPeriodo' => 'nullable|integer',
            'totales.SaldoAnterior' => 'nullable|integer',
            'totales.VlrPagar' => 'nullable|integer',
            'detalle' => 'sometimes|array|min:1',
            'detalle.*.NroLinDet' => 'required|integer',
            'detalle.*.NmbItem' => 'required|string|min:1|max:80',
            'detalle.*.MontoItem' => 'required|integer',
            'detalle.*.IndExe' => 'nullable|integer|between:0,6',
            'detalle.*.QtyItem' => 'nullable|numeric|required_with:detalle.*.PrcItem',
            'detalle.*.PrcItem' => 'nullable|numeric|required_with:detalle.*.QtyItem',
            'detalle.*.DescuentoPct' => 'nullable|numeric|max:100',
            'detalle.*.DescuentoMonto' => 'nullable|numeric|required_with:detalle.*.DescuentoPct',
            'detalle.*.RecargoPct' => 'nullable|numeric|max:100',
            'detalle.*.RecargoMonto' => 'nullable|numeric|required_with:detalle.*.RecargoPct',
            'detalle.*.UnmdItem' => 'nullable|string|max:4',
            'detalle.*.TpoCodigo' => 'nullable|string|max:10',
            'detalle.*.VlrCodigo' => 'nullable|string|max:35',
            'dscRcgGlobal' => 'nullable|array|min:1',
            'dscRcgGlobal.*.NroLinDR' => 'required|integer',
            'dscRcgGlobal.*.TpoMov' => 'required|string|max:1',
            'dscRcgGlobal.*.GlosaDR' => 'nullable|string|max:45',
            'dscRcgGlobal.*.TpoValor' => 'required|string|max:1',
            'dscRcgGlobal.*.ValorDR' => 'required|numeric',
            'dscRcgGlobal.*.IndExeDR' => 'nullable|integer',
            'idDoc' => 'required|array|min:1',
            'idDoc.TipoDTE' => 'required|string|max:3',
            'idDoc.Folio' => 'nullable|integer',
            'idDoc.FchEmis' => 'required|date_format:Y-m-d',
            'idDoc.IndNoRebaja' => 'nullable|integer',
            'idDoc.TipoDespacho' => 'nullable|integer|between:1,3',
            'idDoc.IndTraslado' => 'nullable|integer|between:1,9',
            'idDoc.TpoImpresion' => 'nullable|string|max:1',
            'idDoc.IndServicio' => 'required|integer|between:1,4',
            'idDoc.MntBruto' => 'nullable|integer|between:1,3',
            'idDoc.TpoTranCompra' => 'nullable|integer|between:1,7',
            'idDoc.TpoTranVenta' => 'nullable|integer|between:1,3',
            'idDoc.FmaPago' => 'nullable|integer',
            'idDoc.FmaPagoExp' => 'nullable|string|max:2',
            'idDoc.FchCancel' => 'nullable|date_format:Y-m-d',
            'idDoc.MntCancel' => 'nullable|integer',
            'idDoc.SaldoInsol' => 'nullable|integer',
            'idDoc.PeriodoDesde' => 'nullable date_format:Y-m-d',
            'idDoc.PeriodoHasta' => 'nullable|date_format:Y-m-d',
            'idDoc.MedioPago' => 'nullable|string|max:2',
            'idDoc.TipoCtaPago' => 'nullable|string|max:2',
            'idDoc.NumCtaPago' => 'nullable|string|max:20',
            'idDoc.BcoPago' => 'nullable|string|max:40',
            'idDoc.TermPagoCdg' => 'nullable|string|max:4',
            'idDoc.TermPagoGlosa' => 'nullable|string|max:100',
            'idDoc.TermPagoDias' => 'nullable|integer',
            'idDoc.FchVence' => 'nullable|date_format:Y-m-d',
            'receptor' => 'array|min:1',
            'receptor.RUTRecep' => 'required|cl_rut',
            'receptor.CdgIntRecep' => 'nullable|string|max:20',
            'receptor.RznSocRecep' => 'required|string|max:100',
            'receptor.NumId' => 'nullable|string|max:20',
            'receptor.Nacionalidad' => 'nullable|string|max:3',
            'receptor.TipoDocID' => 'nullable|integer',
            'receptor.IdAdicRecep' => 'nullable|string|max:20',
            'receptor.GiroRecep' => 'required|string|max:40',
            'receptor.Contacto' => 'nullable|string|max:80',
            'receptor.CorreoRecep' => 'nullable|string|max:80',
            'receptor.DirRecep' => 'required|string|max:70',
            'receptor.CmnaRecep' => 'required|string|max:20',
            'receptor.CiudadRecep' => 'nullable|string|max:20',
            'receptor.DirPostal' => 'nullable|string|max:70',
            'receptor.CmnaPostal' => 'nullable|string|max:20',
            'receptor.CiudadPostal' => 'nullable|string|max:20',
            'referencia' => 'nullable|array|min:1',
            'referencia.*.NroLinRef' => 'required|integer',
            'referencia.*.TpoDocRef' => 'required|string|max:3',
            'referencia.*.IndGlobal' => 'nullable|integer',
            'referencia.*.FolioRef' => 'required|string|max:18',
            'referencia.*.FchRef' => 'required|date_format:Y-m-d|after_or_equal:2000-01-01|before_or_equal:2050-12-31',
            'referencia.*.CodRef' => 'nullable|integer|between:1,3|required_if:idDoc.TipoDTE,56|required_if:idDoc.TipoDTE,61',
            'referencia.*.RazonRef' => 'nullable|string|max:90',
            'actividadesEconomicas' => 'sometimes|array|max:4',
            'actividadesEconomicas.*.acteco' => 'integer|exists:actividades_economicas,codigo',
        ];
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        $empresa_id = $this->route('empresa_id') ? $this->route('empresa_id') : null;
        $data['empresa_id'] = $empresa_id;

        if(isset($data['receptor']['RUTRecep'])){
            $rut_receptor_validation = Rut::parse($data['receptor']['RUTRecep'])->quiet()->validate();

            if($rut_receptor_validation){
                $data['receptor']['RUTRecep'] = Rut::parse($data['receptor']['RUTRecep'])->format(Rut::FORMAT_WITH_DASH);
            }
        }

        $data['fechaEmision'] = isset($data['idDoc']['FchEmis']) ? $data['idDoc']['FchEmis'] : date('Y-m-d');
        $data['idDoc']['FchEmis'] = isset($data['idDoc']['FchEmis']) ? $data['idDoc']['FchEmis'] : $data['fechaEmision'];
        $data['neto'] = isset($data['totales']['MntNeto']) ? $data['totales']['MntNeto'] : 0;
        $data['iva'] = isset($data['totales']['IVA']) ? $data['totales']['IVA'] : 0;
        $data['exento'] = isset($data['totales']['MntExe']) ? $data['totales']['MntExe'] : 0;
        $data['total'] = isset($data['totales']['MntTotal']) ? $data['totales']['MntTotal'] : 0;
        $data['receptor']['CiudadRecep'] = isset($data['receptor']['CiudadRecep']) ? substr($data['receptor']['CiudadRecep'], 0, 20) : null;
        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
    }

    private function validarDetalle(array $data, Validator $validator)
    {
        if (isset($data['detalle'])) {
            $lin_det = 1;

            foreach ($data['detalle'] as $index => $value) {
                if (count($data['detalle'][$index]) == 0) {
                    $validator->errors()->add('detalles.'.$index, 'las lineas de detalle no pueden ser vacias');
                }

                if (isset($value['NroLinDet']) && $value['NroLinDet'] != $lin_det) {
                    $validator->errors()->add('detalles.'.$index.'.NroLinDet', 'Correlativo linea detalle erroneo, se esperaba '.$lin_det);
                }

                $qtyItem = isset($value['QtyItem']) ? $value['QtyItem'] : 0;
                $prcItem = isset($value['PrcItem']) ? $value['PrcItem'] : 0;
                $descuentoMonto = isset($value['DescuentoMonto']) ? $value['DescuentoMonto'] : 0;
                $recargoMonto = isset($value['RecargoMonto']) ? $value['RecargoMonto'] : 0;

                $montoItem = ($qtyItem * $prcItem) - $descuentoMonto + $recargoMonto;

                isset($value['IndExe']) && $value['IndExe'] == 1 ? $this->monto_exento += $value['MontoItem'] : $this->monto_iva_incluido += $value['MontoItem'];
                isset($value['IndExe']) && $value['IndExe'] == 1 ? $this->ind_exe_count++ : null;

                if ($montoItem != $value['MontoItem']) {
                    $validator->errors()->add('detalles.'.$index.'.MontoItem',
                        "Monto no concuerda con (QtyItem * PrcItem) - DescuentoMonto + RecargoMonto [{$value['MontoItem']} != {$montoItem}]");
                }

                $lin_det++;
            }

            if (isset($data['TipoDTE']) && $data['TipoDTE'] != 39 && $this->ind_exe_count == ($lin_det - 1)) {
                $validator->errors()->add('detalles.*.IndExe',
                    'IndExe = 1 solo puede estar presente en todas las lineas de detalle cuando el documento es Boleta Exenta Electrónica');
            }
        } else {
            $this->monto_exento = $data['totales']['MntExe'];
            $this->monto_neto = $data['totales']['MntNeto'];
            $this->monto_iva_incluido = $data['totales']['MntTotal'] - $data['totales']['MntExe'];
        }
    }

    private function validarDscRcg(array $data, Validator $validator)
    {
        if (! isset($data['detalle']) && isset($data['dscRcgGlobal'])) {
            $validator->errors()->add('dscRcgGlobal', 'no pueden existir lineas de descuento recargo si no presenta detalle');
        } elseif (isset($data['detalle']) && isset($data['dscRcgGlobal'])) {
            $lin_dscrcg = 1;

            foreach ($data['dscRcgGlobal'] as $index => $value) {
                if (count($data['dscRcgGlobal'][$index]) == 0) {
                    $validator->errors()->add('dscRcgGlobal.'.$index, 'las lineas de descuento recargo no pueden ser vacias');
                }

                if (isset($value['NroLinDR']) && $value['NroLinDR'] != $lin_dscrcg) {
                    $validator->errors()->add('dscRcgGlobal.'.$index.'.NroLinDR', 'Correlativo linea detalle descuento recargo global erroneo, se esperaba '.$lin_dscrcg);
                }

                $valorDR = isset($value['ValorDR']) ? $value['ValorDR'] : 0;

                if (isset($value['TpoMov']) && $value['TpoMov'] == 'D' && ! isset($value['IndExeDR'])) {
                    if (isset($value['TpoValaor']) && $value['TpoValor'] == '%') {
                        $this->monto_iva_incluido -= $this->monto_iva_incluido * ($valorDR / 100);
                    }

                    if (isset($value['TpoValor']) && $value['TpoValor'] == '$') {
                        $this->monto_iva_incluido -= $valorDR;
                    }
                }

                if (isset($value['TpoMov']) && $value['TpoMov'] == 'D' && isset($value['IndExeDR']) && $value['IndExeDR'] == 1) {
                    if (isset($value['TpoValaor']) && $value['TpoValor'] == '%') {
                        $this->monto_exento -= $this->monto_exento * ($valorDR / 100);
                    }

                    if (isset($value['TpoValor']) && $value['TpoValor'] == '$') {
                        $this->monto_exento -= $valorDR;
                    }
                }

                if (isset($value['TpoMov']) && $value['TpoMov'] == 'R' && ! isset($value['IndExeDR'])) {
                    if (isset($value['TpoValaor']) && $value['TpoValor'] == '%') {
                        $this->monto_iva_incluido += $this->monto_iva_incluido * ($valorDR / 100);
                    }

                    if (isset($value['TpoValor']) && $value['TpoValor'] == '$') {
                        $this->monto_iva_incluido += $valorDR;
                    }
                }

                if (isset($value['TpoMov']) && $value['TpoMov'] == 'R' && isset($value['IndExeDR']) && $value['IndExeDR'] == 1) {
                    if (isset($value['TpoValaor']) && $value['TpoValor'] == '%') {
                        $this->monto_exento += $this->monto_exento * ($valorDR / 100);
                    }

                    if (isset($value['TpoValor']) && $value['TpoValor'] == '$') {
                        $this->monto_exento += $valorDR;
                    }
                }

                $lin_dscrcg++;
            }
            //dd($this->monto_neto);
        }
    }

    private function validarRreferencia(array $data, Validator $validator)
    {
        if (isset($data['referencia'])) {
            $lin_ref = 1;

            foreach ($data['referencia'] as $index => $value) {
                if (count($data['referencia'][$index]) == 0) {
                    $validator->errors()->add('referencia.'.$index, 'las lineas de referencias no pueden ser vacias');
                }

                if (isset($value['NroLinRef']) && $value['NroLinRef'] != $lin_ref) {
                    $validator->errors()->add('referencia.'.$index.'.NroLinRef', 'Correlativo linea referencias erroneo, se esperaba '.$lin_ref);
                }

                if (isset($value['CodRef'])) {
                    $validator->errors()->add('referencia.'.$index.'.CodRef', 'Las referencias en las boletas electrónicas no pueden llevar CodRef [codigo]'.$lin_ref);
                }

                $lin_ref++;
            }
        }
    }

    private function validarTotales(array $data, Validator $validator)
    {
        if (isset($data['totales']['MntTotal'])) {
            $this->monto_neto = round($this->monto_iva_incluido / (($this->tasaIVA / 100) + 1));
            $iva = round($this->monto_neto * ($this->tasaIVA / 100));
            $monto_total = $this->monto_neto + $this->monto_exento + $iva;

            $monto_total_aprox_high = $monto_total + 1;
            $monto_total_aprox_min = $monto_total - 1;
            $iva_aprox_high = $iva + +1;
            $iva_aprox_min = $iva - 1;

            if ($data['totales']['MntTotal'] == 0) {
                $validator->errors()->add('totales.MntTotal',
                    'No puede crear boletas con monto total 0');
            }

            if(!isset($data['totales']['IVA'])){
                $validator->errors()->add('totales.IVA',
                    "Monto IVA debe estar presente");
            }else{
                if ($iva != $data['totales']['IVA'] && $iva_aprox_high != $data['totales']['IVA'] && $iva_aprox_min != $data['totales']['IVA']) {
                    $validator->errors()->add('totales.IVA',
                        "Monto IVA no concuerda con detalle [{$data['totales']['IVA']} != {$iva}]");
                }
            }


            if (isset($data['totales']['MntExe']) && $data['totales']['MntExe'] != $this->monto_exento) {
                $validator->errors()->add('totales.MntExe',
                    "Monto Exento no concuerda con detalle [{$data['totales']['MntExe']} != {$this->monto_exento}]");
            }

            if (isset($data['totales']['MntNeto']) && $data['totales']['MntNeto'] != $this->monto_neto) {
                $validator->errors()->add('totales.MntNeto',
                    "Monto Neto no concuerda con detalle [{$data['totales']['MntNeto']} != {$this->monto_neto}]");
            }

            if ($data['totales']['MntTotal'] != $monto_total && $data['totales']['MntTotal'] != $monto_total_aprox_high && $data['totales']['MntTotal'] != $monto_total_aprox_min) {
                $validator->errors()->add('totales.MntTotal',
                    "Monto Total no concuerda con detalle [{$data['totales']['MntTotal']} != {$monto_total}]");
            }
            //Monto neto + Monto no afecto o
            //exento  + IVA + Impuestos Adicionales
            //+ Impuestos Específicos + Iva Margen Comercialización +IVA Anticipado +Garantía por depósito de envases oembalajes
            //-Crédito empresas constructoras -IVA Retenido productos(en caso de facturas de compra)- Valor Neto Comisiones y Otros Cargos
            //- IVA  Comisiones y Otros Cargos -Valor Comisiones y Otros Cargos NoAfectos o Exentos
        }

        if (isset($data['TipoDTE']) && $data['TipoDTE'] == 41) {
            if (isset($data['totales']['MntExe']) && $data['totales']['MntExe'] <= 0) {
                $validator->errors()->add('totales.MntExe', 'el atributo MntExe debe ser mayor a 0 en boletas exentas electrónicas');
            }

            if (isset($data['totales']['MntNeto']) && $data['totales']['MntNeto'] > 0) {
                $validator->errors()->add('totales.MntNeto', 'el atributo MntNeto no puede ser mayor a 0 en boletas exentas electrónicas');
            }

            if (isset($data['totales']['IVA']) && $data['totales']['IVA'] > 0) {
                $validator->errors()->add('totales.IVA', 'el atributo IVA no puede ser mayor a 0 en boletas exentas electrónicas');
            }
            //if(isset($data['Totales']['']))
        }

        if (! isset($data['totales']['IVA']) && isset($data['totales']['MntNeto']) && $data['totales']['MntNeto'] > 0) {
            $validator->errors()->add('totales.IVA', 'el atributo IVA debe estar presente cuando monto neto es mayor que 0');
        }

        if (isset($data['totales']['TasaIVA']) && $data['totales']['TasaIVA'] != $this->tasaIVA) {
            $validator->errors()->add('totales.TasaIVA', "La tasa {$data['totales']['TasaIVA']} no corresponde a la tasa registrada en el Sistema");
        }

        if (isset($data['totales']['IVA']) && isset($data['totales']['MntNeto']) && $iva != $data['totales']['IVA'] && $iva_aprox_high != $data['totales']['IVA'] && $iva_aprox_min != $data['totales']['IVA']) {
            $validator->errors()->add('totales.IVA', 'el atributo IVA no coincide con la tasa del '.$this->tasaIVA.'%');
        }
    }

    private function validarActividadesEconomicas(Validator $validator)
    {
        $data = $this->all();
        if (isset($data['actividadesEconomicas'])) {
            try {
                foreach ($data['actividadesEconomicas'] as $index => $value) {
                    if (count($data['actividadesEconomicas'][$index]) == 0) {
                        $validator->errors()->add('actividadesEconomicas.'.$index, 'las lineas de actividades economicas no pueden ser vacias');
                    }

                    //$data['actividadesEconomicas'][$index] = 1;

                    if (isset($value['acteco'])) {
                        $actividad = $this->actividadEconomicaRepository->findWhere(['codigo' => $value['acteco']], ['id']);
                        $actividad_empresa = ActividadEconomicaEmpresa::where('actividad_economica_id', $actividad[0]->id)->first();

                        if (! empty($actividad_empresa)) {
                            $data['actividadesEconomicas'][$index]['actividad_economica_id'] = $actividad[0]->id;
                        } else {
                            $validator->errors()->add('actividadesEconomicas.'.$index, 'La actividad economica no esta autorizada');
                        }
                    }
                }

                $this->getInputSource()->replace($data);
            } catch (\Throwable $e) {
                $validator->errors()->add('sistema',
                    'Error de sistema, favor contactarse con el administrador con los siguientes datos: '.$e->getMessage().' en linea '.$e->getLine());
            }
        }
    }

    private function validarTipoDocumento(Validator $validator)
    {
        $data = $this->all();
        if (isset($data['idDoc']['TipoDTE'])) {
            if (ctype_digit($data['idDoc']['TipoDTE'])) {
                $result_tipodte = \App\Components\TipoDocumento::getTipoDocumentoId($data['idDoc']['TipoDTE']);
                if ($result_tipodte == 0) {
                    $tipoDocumento = null;
                } else {
                    $tipoDocumento = new \stdClass();
                    $tipoDocumento->id = $result_tipodte;
                }
            } elseif (is_string($data['idDoc']['TipoDTE'])) {
                if (! isset($data['empresa_id'])) {
                    $validator->errors()->add('empresa_id', 'el campo empresa_id es obligatorio');
                }
                $tipoDocumento = TipoDocumento::where('tipoDTE', $data['idDoc']['TipoDTE'])->where('empresa_id', $data['empresa_id'])->first();
            }

            if (! empty($tipoDocumento)) {
                $data['tipo_documento_id'] = $tipoDocumento->id;

                if (isset($data['empresa_id'])) {
                    $autorizado = DocumentoAutorizado::where('empresa_id', $data['empresa_id'])->where('tipo_documento_id', $tipoDocumento->id)->first();

                    if (empty($autorizado)) {
                        $validator->errors()->add('idDoc.TipoDTE', 'No corresponde a un documento autorizado');
                    }
                }

                $this->getInputSource()->replace($data);
            } else {
                $validator->errors()->add('idDoc.TipoDTE', 'No es valido');
            }
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $data = $validator->getData();

            $this->validarTipoDocumento($validator);
            $this->validarDetalle($data, $validator);
            $this->validarDscRcg($data, $validator);
            $this->validarRreferencia($data, $validator);
            $this->validarTotales($data, $validator);
            $this->validarActividadesEconomicas($validator);
        });
    }
}
