<?php

namespace App\Http\Requests\Event;

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
          'year' => 'required',
          'catagory_id' => 'required',
          'event' => [
              'required', 
              'min:10', 
              Rule::unique('events')->ignore(request()->id),
            ]
        ];
        
    }
    public function attributes()
    {
        return [
            'catagory_id' => 'catagory name',
        ];
    }
}
