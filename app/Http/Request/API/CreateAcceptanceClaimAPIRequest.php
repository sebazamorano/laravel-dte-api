<?php

namespace App\Http\Request\API;

use App\Http\Request\APIRequest;
use App\Models\SII\AcceptanceClaim;
use Illuminate\Validation\Validator;

class CreateAcceptanceClaimAPIRequest extends APIRequest
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

    public function getValidatorInstance()
    {
        $data = $this->all();
        $empresa_id = $this->route('empresa_id') ? $this->route('empresa_id') : null;
        $data['company_id'] = $empresa_id;
        $this->getInputSource()->replace($data);

        /*modify data before send to validator*/

        return parent::getValidatorInstance();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return AcceptanceClaim::$rules;
    }

    public function actionIsRegistered(Validator $validator)
    {
        $data = $this->all();
        $action = new AcceptanceClaim();
        $response = $action->isUnique($data);

        if (! $response) {
            $validator->errors()->add('action', 'previously registered action');
        }
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function (Validator $validator) {
            $this->actionIsRegistered($validator);
        });
    }
}
