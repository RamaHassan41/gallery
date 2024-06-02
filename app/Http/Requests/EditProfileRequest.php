<?php

namespace App\Http\Requests;
use App\Traits\GeneralTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
class EditProfileRequest extends FormRequest
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
            'name'=>'string|min:4',
            'email'=>'string|email|unique:admins,email|unique:artists,email|unique:users,email',
            'password'=>'string|min:8',
            'c_password'=>'same:password',
            //'image'=>'sometimes',
            'image'=>'image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_image'=>'boolean',
            'user_name'=>'string|unique:admins,user_name|unique:artists,user_name|unique:users,user_name',
            'gender'=>'string|in:male,female',
            'expertise'=>'string',
            'specialization'=>'string',
            'biography'=>'string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->sendError([$validator->errors(),'Please validate error'],422));
    }
}
