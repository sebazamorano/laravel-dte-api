<?php

namespace App\Http\Requests\API;

use App\Http\Request\APIRequest;
use App\Models\Catalog\Product\Product;

class CreateProductAPIRequest extends APIRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Product::$rules;
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        $data['company_id'] = $this->route('empresa_id');
        $this->getInputSource()->replace($data);
        /*modify data before send to validator*/
        return parent::getValidatorInstance();
    }
}
