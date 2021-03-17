<?php

namespace App\Http\Requests;

use App\Models\Sucursal as CompanyBranchOffice;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyBranchOfficeRequest extends FormRequest
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
    public function attributes()
    {
        return CompanyBranchOffice::$labels;
    }

    public function rules()
    {
        return [
            'tipo' => 'required',
            'nombre' => 'max:40|required',
            'direccion' => 'max:200|required',
            'direccionXml' => 'max:60',
            'comuna' => 'max:20|required',
            'ciudad' => 'max:20|required',
        ];
    }
}
