<?php

namespace App\Http\Requests;

use App\Models\Sucursal as CompanyBranchOffice;
use Illuminate\Foundation\Http\FormRequest;

class CreateCompanyBranchOfficeRequest extends FormRequest
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
            'empresa_id' => 'required|exists:empresas,id',
            'tipo' => 'required',
            'nombre' => 'max:40|required',
            'direccion' => 'max:200|required',
            'direccionXml' => 'max:60',
            'comuna' => 'max:20|required',
            'ciudad' => 'max:20|required',
        ];
    }

    public function attributes()
    {
        return CompanyBranchOffice::$labels;
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        $empresa = $this->route('company') ? $this->route('company') : null;
        $data['empresa_id'] = $empresa->id;

        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
    }
}
