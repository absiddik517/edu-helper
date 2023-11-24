<?php

namespace App\Http\Requests\Catagory;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'type' => 'required',
            'name' => 'required|min:4|unique:catagories'
        ];
    }
    
    public function attributes()
    {
        return [
            'type' => 'catagory type',
            'name' => 'catagory name',
        ];
    }
}
