<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Documento extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);

        $array['file'] = $this->file;
        $array['idDoc'] = $this->idDoc;
        $array['actividadesEconomicas'] = $this->actividadesEconomicas;
        $array['dscRcgGlobal'] = $this->dscRcgGlobal;
        $array['tipoDocumento'] = $this->tipoDocumento;
        unset($array['id_doc']);
        unset($array['actividades_economicas']);
        unset($array['tipo_documento']);
        unset($array['dsc_rcg_global']);

        return $array;
        /*
        return [
            'id' => $this->id,
            'empresa_id' => $this->empresa_id,
            'sucursal_id' => $this->sucursal_id,
            'tipo_documento_id' => $this->tipo_documento_id,
            'entidad_id' => $this->entidad_id,
            'caf_id' => $this->caf_id,
            'certificado_empresa_id' => $this->certificado_empresa_id,
            'observaciones' => $this->observaciones,
            'fechaEmision' => $this->fechaEmision,
            'folio' => $this->folio,
            'folioString' => $this->folioString,
            'tipo_pago_id' => $this->tipo_pago_id,
            'tipoDte' => $this->tipoDte,
            'folio' => $this->folio,
            'tipo_plazo_id' => $this->tipo_plazo_id,
            'neto' => $this->neto,
            'exento' => $this->exento,
            'iva' => $this->iva,
            'total' => $this->total,
            'costo' => $this->costo,
            'margen' => $this->margen,
            'saldo' => $this->saldo,
            'cancelado' => $this->cancelado,
            'fechaPago' => $this->fechaPago,
            'nota' => $this->nota,
            'TSTED' => $this->TSTED,
            'TmstFirma' => $this->TmstFirma,
            'IO' => $this->IO,
            'user_id' => $this->user_id,
            'estadoPago' => $this->estadoPago,
            'estado' => $this->estado,
            'glosaEstadoSii' => $this->glosaEstadoSii,
            'glosaErrSii' => $this->glosaErrSii,
            'estadoSii' => $this->estadoSii,
            'errCode' => $this->errCode,
            'idDoc' => $this->idDoc,
            'receptor' => $this->receptor,
            'transporte' => $this->transporte,
            'totales' => $this->totales,
            'detalle' => $this->detalle,
            'referencia' => $this->referencia,
            'actividadesEconomicas' => $this->actividadesEconomicas,
            'dscRcgGlobal' => $this->dscRcgGlobal,
            'tipoDocumento' => $this->tipoDocumento,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at
        ];*/
    }
}
