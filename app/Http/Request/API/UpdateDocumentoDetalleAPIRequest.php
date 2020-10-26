<?php

namespace App\Http\Request\API;

use App\Models\DocumentoDetalle;
use App\Http\Request\APIRequest;

class UpdateDocumentoDetalleAPIRequest extends APIRequest
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
        return DocumentoDetalle::$rules;
    }
}
