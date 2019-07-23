<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addorgRequest extends FormRequest
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
            'parid' => 'required',
            'code' => 'required|string|max:5',
            'name' => 'required|string|min:4|max:30',
            'tname' => 'required|string|min:4|max:50',
            'ref' => 'required|max:1',
            'erp' => 'required|size:7',
        ];
    }
    
    public function messages()
    {
        return [
            'code.required' => 'new Code is required',
            'name.required'  => 'new Organization Name is required',
            'tname.required'  => 'Organization Name in Thai is required',
            'ref.required'  => 'Organization Level below is required',
            'erp.required'  => 'GL Code is required',
            'erp.size'  => 'GL Code must be 7 digits',
        ];
    }

    public function filters()
    {
        return [
            'code' => 'trim|capitalize',
            'name' => 'trim|capitalize|escape'
        ];
    }
}
