<?php

namespace Zjalen\Leadmin\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminMenuRequest extends FormRequest
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
                    'order'=> 'max:10',
                    'title'=> 'max:50',
                    'icon'=> 'max:50',
                    'url'=> 'max:100',

                ];
            }
            // UPDATE
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'order'=> 'max:10',
                    'title'=> 'max:50',
                    'icon'=> 'max:50',
                    'url'=> 'max:100',

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
            'url.max' => '字符长度不超过100',

        ];
    }
}
