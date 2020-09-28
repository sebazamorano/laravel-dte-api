<?php

namespace App\Http\Requests\API;

use App\Models\Documento;
use App\Http\Request\APIRequest;

class UpdateDocumentoAPIRequest extends APIRequest
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
        return Documento::$rules;
    }
}
