<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyControllerRequest extends FormRequest
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
            'razonSocial' => 'required|max:150|min:5',
            'giro' => 'required|max:80|min:10',
            'fechaResolucion' => 'nullable|sometimes|date',
            'numeroResolucion' => 'nullable|sometimes|integer',
            'fechaResolucionBoleta' => 'nullable|sometimes|date',
            'numeroResolucionBoleta' => 'nullable|sometimes|integer',
            'contactoSii' => 'nullable|sometimes|email',
            'contactoEmpresas' => 'nullable|sometimes|email',
            'passwordSii' => 'nullable|sometimes|string',
        ];
    }
}
