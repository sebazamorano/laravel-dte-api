<?php

namespace App\Http\Requests\API;

use App\Models\Documento;
use App\Models\TipoDocumento;
use App\Http\Request\APIRequest;
use App\Models\TicketCreditNote;
use Illuminate\Validation\Validator;

class CreateTicketCreditNoteAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'empresa_id' => 'integer|exists:empresas,id',
            'documento_id' => 'nullable|integer|exists:documentos,id',
            'fechaEmision' => 'required|date_format:Y-m-d',
            'montoNeto' => 'nullable|numeric|required_with:iva',
            'montoExento' => 'nullable|numeric|required_if:tipoBoleta,41',
            'iva' => 'nullable|numeric|required_unless:tipoBoleta,41',
            'tasaIva' => 'nullable|numeric|required_with:iva',
            'montoTotal' => 'required|numeric',
            'folioBoleta' => 'required|integer',
            'folioNotaCredito' => 'required|integer',
            'tipoBoleta' => 'required|numeric|in:39,41',
        ];
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        $empresa_id = $this->route('empresa_id') ? $this->route('empresa_id') : null;
        $data['empresa_id'] = $empresa_id;
        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
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
            $tasaIva = 19;
            $data = $validator->getData();
            if(isset($data['tipoBoleta'])){
                $document_type = TipoDocumento::where('tipoDTE', $data['tipoBoleta'])->first();
                /* @var Documento  $ticket */
                $ticket = Documento::where('tipo_documento_id', $document_type->id)->where('folio', $data['folioBoleta'])->first();

                if (! $ticket) {
                    $validator->errors()->add('folioBoleta',
                        'Boleta no existe en sistema facturafacil');
                } else {
                    if (isset($data['montoNeto'])) {
                        $iva = round($data['montoNeto'] * ($data['tasaIva'] / 100));
                        $monto_total = $data['montoNeto'] + $data['montoExento'] + $iva;

                        if ($iva != $data['iva']) {
                            $validator->errors()->add('iva',
                                "Monto IVA no concuerda con detalle [{$data['iva']} != {$iva}]");
                        }

                        /*if(isset($data['totales']['montoExento']) && $data['montoExento'] != $this->monto_exento){
                            $validator->errors()->add('totales.MntExe',
                                "Monto Exento no concuerda con detalle [{$data['totales']['MntExe']} != {$this->monto_exento}]");
                        }

                        if(isset($data['totales']['montoNeto']) && $data['montoNeto'] != $this->monto_neto){
                            $validator->errors()->add('totales.MntNeto',
                                "Monto Neto no concuerda con detalle [{$data['totales']['MntNeto']} != {$this->monto_neto}]");
                        }*/

                        if ($data['montoTotal'] != $monto_total) {
                            $validator->errors()->add('montoTotal',
                                "Monto Total no concuerda con detalle [{$data['montoTotal']} != {$monto_total}]");
                        }
                    }

                    if (isset($data['tipoBoleta']) && $data['tipoBoleta'] == 41) {
                        if (isset($data['montoExento']) && $data['montoExento'] <= 0) {
                            $validator->errors()->add('montoExento', 'el atributo Monto Exento debe ser mayor a 0 en boletas exentas electrónicas');
                        }

                        if (isset($data['montoNeto']) && $data['montoNeto'] > 0) {
                            $validator->errors()->add('montoNeto', 'el atributo Monto Neto no puede ser mayor a 0 en boletas exentas electrónicas');
                        }

                        if (isset($data['iva']) && $data['iva'] > 0) {
                            $validator->errors()->add('iva', 'el atributo IVA no puede ser mayor a 0 en boletas exentas electrónicas');
                        }
                    }

                    if (! isset($data['iva']) && isset($data['montoNeto']) && $data['montoNeto'] > 0) {
                        $validator->errors()->add('iva', 'el atributo IVA debe estar presente cuando monto neto es mayor que 0');
                    }

                    if (isset($data['tasaIva']) && $data['tasaIva'] != $tasaIva) {
                        $validator->errors()->add('tasaIva', "La tasa {$data['tasaIva']} no corresponde a la tasa registrada en el Sistema");
                    }

                    if (isset($data['iva']) && isset($data['montoNeto']) && round($data['montoNeto'] * ($tasaIva / 100)) != $data['iva']) {
                        $validator->errors()->add('iva', 'el atributo IVA no coincide con la tasa del '.$tasaIva.'%');
                    }

                    $tcn_1 = TicketCreditNote::where('empresa_id', $data['empresa_id'])->where('folioNotaCredito', $data['folioNotaCredito'])->get();

                    if ($tcn_1) {
                        /* @var TicketCreditNote  $ticket_credit_note */
                        $ammount = 0;
                        foreach ($tcn_1 as  $ticket_credit_note) {
                            $ammount += $ticket_credit_note->montoTotal;
                        }

                        $a_rebajar = $ticket->total - $ammount;

                        if ($a_rebajar < 0) {
                            $validator->errors()->add('folioBoleta',
                                'El valor de la nota de credito supera el valor de la boleta');
                        } else {
                            if ($data['folioNotaCredito'] >= $a_rebajar) {
                                $validator->errors()->add('folioBoleta',
                                    'Monto Boleta ya fue rebajado en su totalidad');
                            }
                        }
                    }
                }
            }

        });
    }
}
