<?php

namespace App\Http\Request\API;

use App\Http\Request\APIRequest;
use App\Models\SII\AcceptanceClaim;

class UpdateAcceptanceClaimAPIRequest extends APIRequest
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
        return AcceptanceClaim::$rules;
    }
}
