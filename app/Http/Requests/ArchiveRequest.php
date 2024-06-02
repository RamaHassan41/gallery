<?php

namespace App\Http\Requests;
use App\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ArchiveRequest extends FormRequest
{
    use GeneralTrait;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'=>'required|string',
            'details'=>'string',
            'paintings'=>'required|array',
            'paintings.*.title' => 'required|string',
            'paintings.*.description' => 'string|nullable',
            'paintings.*.price' => 'numeric|nullable',
            'paintings.*.size' => 'nullable',
            'paintings.*.url' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'paintings.*.type_id' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError([$validator->errors(),'Please validate error'],422));
    }
}
