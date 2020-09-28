<?php

namespace App\Http\Requests\API;

use App\Models\Sucursal;
use App\Http\Request\APIRequest;

class UpdateSucursalAPIRequest extends APIRequest
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
        return Sucursal::$rules;
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        $data['empresa_id'] = $this->route('empresa_id');
        $this->getInputSource()->replace($data);
        /*modify data before send to validator*/
        return parent::getValidatorInstance();
    }
}
