<?php

namespace Zjalen\Leadmin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminPermissionRequest extends FormRequest
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
                    'name'=> 'max:50',
                    'slug'=> 'max:50',
                    'http_method'=> 'max:50',
                    'http_path'=> 'max:50',

                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name'=> 'max:50',
                    'slug'=> 'max:50',
                    'http_method'=> 'max:50',
                    'http_path'=> 'max:50',

                ];
            }
            case 'GET':
            case 'DELETE':
            default:
            {
                return [];
            };
        }
    }

    public function messages()
    {
        return [
            // Validation messages
            'http_path.max' => '字符长度不超过50',

        ];
    }
}
