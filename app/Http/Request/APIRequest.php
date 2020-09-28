<?php

namespace App\Http\Request;

use Response;
use App\Models\Empresa;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Utils\ResponseUtil;

class APIRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        /* @var Empresa $empresa */
        $empresa_id = $this->route('empresa_id') ? $this->route('empresa_id') : $this->request->input('empresa_id');

        if (! $empresa_id) {
            return false;
        }

        $empresa = Empresa::find($empresa_id);

        if (empty($empresa)) {
            return false;
        }

        if ($this->user()->isSuperAdmin()) {
            return true;
        }

        return $empresa->isEmployee($this->user()->id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $status = 422;
        $error_keys = [];
        foreach ($validator->getMessageBag()->getMessages() as $key => $value) {
            foreach ($value as $item =>$error_message) {
                $error_keys[$key][] = $error_message;
            }
        }

        throw new HttpResponseException(response()->json([
            'message' => 'The given data was invalid',
            'errors' => $error_keys,
            'status_code' => $status,
        ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY));
    }

    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $messages = implode(' ', array_flatten($errors));

        return Response::json(ResponseUtil::makeError($messages), 400);
    }
}
