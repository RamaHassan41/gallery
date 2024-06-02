<?php

namespace App\Http\Requests;
use App\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
//use App\Http\Controllers\BaseController;
class RegisterRequest extends FormRequest
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
            'name'=>'required|string|min:4',
            'email'=>'required|string|email|unique:admins,email|unique:artists,email|unique:users,email',
            'password'=>'required|min:8',
            'c_password'=>'required|same:password',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError([$validator->errors(),'Please validate error'],422));
    }
}
