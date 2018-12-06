<?php

namespace Zjalen\Leadmin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
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
        switch($this->method()) {
            // CREATE
            case 'POST':
            {
                return [
                    'username'=> 'max:20',
                    'password'=> 'max:255',
                    'remember_token'=> 'max:120',
                    'name'=> 'max:50',
                    'email'=> 'max:100',
                    'avatar'=> 'max:100',

                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'username'=> 'max:20',
                    'password'=> 'max:255',
                    'remember_token'=> 'max:120',
                    'name'=> 'max:50',
                    'email'=> 'max:100',
                    'avatar'=> 'max:100',

                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            }
        }
    }

    public function messages()
    {
        return [
            // Validation messages
            'avatar.max' => '字符长度不超过100',

        ];
    }
}
