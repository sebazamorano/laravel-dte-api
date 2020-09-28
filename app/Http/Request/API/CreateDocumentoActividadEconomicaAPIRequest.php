<?php

namespace App\Http\Requests\API;

use App\Http\Request\APIRequest;
use App\Models\DocumentoActividadEconomica;

class CreateDocumentoActividadEconomicaAPIRequest extends APIRequest
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
        return DocumentoActividadEconomica::$rules;
    }
}
