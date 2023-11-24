<?php

namespace App\Http\Requests\Catagory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
            'name' => [
              'required', 
              'min:4', 
              Rule::unique('catagories')->ignore(request()->id),
            ],
            "type"=> 'required'
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
