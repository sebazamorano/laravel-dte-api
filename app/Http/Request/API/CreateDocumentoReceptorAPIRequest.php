<?php

namespace App\Http\Request\API;

use App\Models\DocumentoReceptor;
use App\Http\Request\APIRequest;

class CreateDocumentoReceptorAPIRequest extends APIRequest
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
        return DocumentoReceptor::$rules;
    }
}
