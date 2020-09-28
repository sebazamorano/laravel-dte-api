<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmpresaResource extends JsonResource
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

        $array['comuna'] = $this->comuna;
        $array['provincia'] = $this->provincia;
        $array['region'] = $this->region;
        $array['sucursales'] = $this->sucursales;
        $array['actividadesEconomicasEmpresas'] = $this->actividadesEconomicasEmpresas;
        $array['documentosAutorizados'] = $this->documentosAutorizados;
        $array['logo'] = $this->logo;

        return $array;
    }
}
