<?php

namespace App\Http\Requests\API;

use App\Models\Empresa;
use App\Http\Request\APIRequest;
use Carbon\Carbon;
use Freshwork\ChileanBundle\Rut;

class CreateEmpresaAPIRequest extends APIRequest
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
            'rut' => 'max:10|required|cl_rut|unique:empresas',
            'razonSocial' => 'max:150|required|min:5',
            'direccion' => 'max:70|required',
            'region_id' => 'required|numeric|exists:regiones,id',
            'provincia_id' => 'required|numeric|exists:provincias,id',
            'comuna_id' => 'required|numeric|exists:comunas,id',
            'giro' => 'max:80|required|min:10',
            'correoRecepcion' => 'max:80',
            'passwordCorreo' => 'max:100',
            'servidorSmtp' => 'max:255',
            'fechaResolucion' => 'date',
            'numeroResolucion' => 'integer',
            'fechaResolucionBoleta' => 'date',
            'numeroResolucionBoleta' => 'integer',
        ];
    }

    public function getValidatorInstance()
    {
        $data = $this->all();
        $data['rut'] = Rut::parse($this->input('rut'))->format(Rut::FORMAT_WITH_DASH);

        $fechaResolucion = $this->input('fechaResolucion');
        $fechaResolucionBoleta = $this->input('fechaResolucionBoleta');

        if(isset($fechaResolucion)){
            $data['fechaResolucion'] = Carbon::createFromFormat('Y-m-d\TH:i:s.uO', $fechaResolucion)->format('Y-m-d H:i:s');
        }

        if(isset($fechaResolucionBoleta)){
            $data['fechaResolucionBoleta'] = Carbon::createFromFormat('Y-m-d\TH:i:s.uO', $fechaResolucionBoleta)->format('Y-m-d H:i:s');
        }

        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
    }
}
