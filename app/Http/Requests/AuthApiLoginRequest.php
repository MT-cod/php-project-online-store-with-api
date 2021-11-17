<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class AuthApiLoginRequest extends FormRequest
{
    /**
     * Правила валидации при логине.
     *
     * @return array
     */
    public function rules()
    {
        return [];
        /*return [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8']
        ];*/
        /*return [
            'current_password' => 'required_with:password',
            'password' => 'required_with:current_password|confirmed',
            'name' => 'required|unique:users,name,' . $this->user()->id
        ];*/
    }

    /**
     * Надстройка экземпляра валидатора.
     *
     * @param  Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $user = User::where('email', $this->email)->first();
        if (!$user || !Hash::check($this->password, $user->password)) {
            $validator->errors()->add('error', 'Не удалось авторизовать пользователя.');
        }
        /*$validator->after(function ($validator) {
            if ($this->somethingElseIsInvalid()) {
                $validator->errors()->add('error', 'Не удалось авторизовать пользователя.');
            }
        });*/
        /*$validator->after(function ($validator) {
            $currentPassword = $this->current_password;

            if (! empty($currentPassword) && ! Hash::check($currentPassword, $this->user()->password)) {
                $validator->errors()->add('current_password', 'Текущий пароль не совпадает с указанным паролем.');
            }

            if (! empty($currentPassword) && ! strcmp($currentPassword, $this->password)) {
                $validator->errors()->add('password', 'Новый пароль не может совпадать с текущим паролем.');
            }

            if (! empty($this->password) && mb_strlen($this->password) < 6) {
                $validator->errors()->add('password', 'Пароль должен содержать минимум 6 символов.');
            }
        });*/
    }
}
