<?php

namespace App\Http\Request\API;

use Freshwork\ChileanBundle\Rut;
use App\Http\Request\APIRequest;

class IndexDetalleRespuestaDteXmlAPIRequests extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'rut' => 'nullable|cl_rut',
            'fecha_emision_inicial' => 'nullable|date_format:Y-m-d',
            'fecha_emision_final' => 'nullable|date_format:Y-m-d',
            'folio' => 'nullable|integer',
            'tipoDte' => 'nullable|integer|exists:tipos_documentos,tipoDTE',
        ];
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        if ($this->input('rut') != '' & $this->input('rut') != null) {
            $data['rut'] = Rut::parse($this->input('rut'))->format(Rut::FORMAT_WITH_DASH);
            $this->getInputSource()->replace($data);
        }

        if ($this->input('tipoDte') == null) {
            unset($data['tipoDte']);
            $this->getInputSource()->replace($data);
        }

        if ($this->input('folio') == null) {
            unset($data['folio']);
            $this->getInputSource()->replace($data);
        }
        /*modify data before send to validator*/
        return parent::getValidatorInstance();
    }
}
