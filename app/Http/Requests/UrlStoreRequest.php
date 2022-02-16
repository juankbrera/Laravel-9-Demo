<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UrlStoreRequest extends FormRequest
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
        return [
            'original_url' => 'required|url'
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'original_url.url' => 'The url is invalid',
            'original_url.required' => 'The url is required',
        ];
    }
}
