<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Freshwork\ChileanBundle\Rut;
use Illuminate\Foundation\Http\FormRequest;

class StoreSyncSiiRequest extends FormRequest
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
        return [
            'identity_card ' => 'Rut Empresa',
            'original' => 'Certificado Digital',
            'password' => 'Contraseña Certificado Digital',
        ];
    }

    public function rules()
    {
        return [
            'identity_card' => 'required|cl_rut|unique:empresas,rut',
            'original' =>  'required|file',
            'password' =>'required|string|max:45',
            'documentoAutorizado' => 'sometimes|array|min:1',
        ];
    }

    public function messages()
    {
        return [
            'identity_card.cl_rut' => 'El Rut ingresado no es valido',
            'identity_card.unique' => 'El Rut ya ha sido registrado',
        ];
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        $data['identity_card'] = Rut::parse($this->input('identity_card'))->quiet()->format(Rut::FORMAT_WITH_DASH);
        $this->getInputSource()->replace($data);
        /*modify data before send to validator*/
        return parent::getValidatorInstance();
    }
}
