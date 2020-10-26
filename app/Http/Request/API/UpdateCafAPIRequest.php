<?php

namespace App\Http\Request\API;

use App\Models\Caf;
use App\Http\Request\APIRequest;

class UpdateCafAPIRequest extends APIRequest
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
            'enUso' => 'sometimes|integer',
            'fa' => 'sometimes|date',
            'fechaVencimiento' => 'sometimes|after:fa',
            'folioDesde' => 'sometimes|integer',
            'folioHasta' => 'sometimes|integer',
            'folioUltimo' => 'sometimes|integer',
        ];
    }
}
