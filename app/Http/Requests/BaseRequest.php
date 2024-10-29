<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Configure the validator instance.
     * If there are extra fields, fail the validation with a custom message
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $input = $this->all();
        $allowedFields = array_keys($this->rules());
        $extraFields = array_diff(array_keys($input), $allowedFields);

        if (count($extraFields) > 0) {
            $validator->after(function ($validator) use ($extraFields) {
                foreach ($extraFields as $extraField) {
                    $validator->errors()->add($extraField, 'This field does not exist or is not allowed');
                }
            });
        }
    }
}
