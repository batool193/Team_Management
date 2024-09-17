<?php

namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProjectFormRequest extends FormRequest
{
    /**
     * Indicates if the validation should stop on the first failure
     *
     * @var bool
     */
    protected $stopOnFirstFailure = true;
    /**
     * Determine if the user is authorized to make this request
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:projects,name',
            'description' => 'nullable|string',
        ];
        // Adjust rules for PATCH or PUT requests
        if ($this->isMethod('patch') || $this->isMethod('put')) {
            $rules = [
              'name' => 'nullable|string|max:255|unique:projects,name',
            'description' => 'nullable|string',

            ];
        }

        return $rules;
    }
    /**
     * Get the custom validation messages
     *
     * @return array<string, string>
     */

    public function messages()
    {
        return [
            'required' => ':attribute is required',
            'string' => ':attribute must be a string',
            'unique' => ':attribute has already been taken',
            'max' => ':attribute may not be greater than 255 characters',
            'regex' => ':attribute must contain only letters',

        ];
    }
    /**
     * Get custom attributes for validator errors
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'name' => 'project name',
            'description' => 'project description',
        ];
    }
    /**
     * Handle a failed validation attempt
     *
     * @param Validator $validator
     * @throws HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'validation error',
            'errors' => $validator->errors(),
        ], 400));
    }
}
