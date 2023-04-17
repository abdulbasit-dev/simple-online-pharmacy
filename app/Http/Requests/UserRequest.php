<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Log;

class UserRequest extends FormRequest
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

        if (request()->isMethod("POST")) {
            $checkUniqueEmail = "unique:users,email";
            $checkUniquePhone = "unique:users,phone";
            $phoneAlt = ["unique:users,phone_alt", "nullable"];
            $passwordRule = ['required', 'min:8'];
        } elseif (request()->isMethod("PUT") || request()->isMethod("PATCH")) {
            $checkUniqueEmail = "unique:users,email, " . $this->user->id;
            $checkUniquePhone = "unique:users,phone, " . $this->user->id;
            $phoneAlt = ["unique:users,phone_alt, " . $this->user->id, "nullable"];
            $passwordRule = [];
        }

        return [
            "name"              => ['required'],
            "email"             => ['required', $checkUniqueEmail],
            "password"          => $passwordRule,
            "address"           => ['sometimes', 'nullable'],
            "phone"             => ['required', $checkUniquePhone],
            "phone_alt"         => $phoneAlt,
            // 'role'              => ['required'],
        ];
    }

    // if password is left blank in  form, password can not be sent as null
    // protected function prepareForValidation()
    // {
    //     if ($this->password == null) {
    //         $this->request()->remove('password');
    //     }
    // }
}
