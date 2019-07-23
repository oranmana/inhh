<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addjobRequest extends FormRequest
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
            'code' => 'string|max:15',
            'name' => 'required|string|min:4|max:40',
            'tname' => 'required|string|min:4|max:60',
            'cat' => 'required|min:1',
            'type' => 'integer',
            'erp' => 'required|size:7',
            'sub' => 'required|min:1',
            'gl' => 'required|integer|between:18,45',
            'sfx' => 'required',
            'pj' => 'required|min:1',
            'dir' => 'required|numeric|min:9000',
        ];
    }
    
    public function messages()
    {
        return [
            'parid.required' => 'Critical Error, please contact Webmaster',
            'code.max' => 'Code length must be less than 15 characters',
            'name.required'  => 'new Organization Name is required',
            'name.max'  => 'Title length must be less than 40 characters',
            'tname.required'  => 'Title in Thai is required',
            'tname.max'  => 'Title length must be less than 60 characters',
            'cat.required'  => 'Education Level must be determined',
            'type.integer'  => 'Allowance must be Integer Number',
            'erp.required'  => 'GL Code is required',
            'erp.size'  => 'GL Code must be 7 digits',
            'sub.required'  => 'T/O Number must be specified',
            'gl.required'  => 'Minimum age requirement must be set',
            'gl.digits_between'  => 'Minimum age must between 18 and 45',
            'gl.lte'  => 'Min.age required and less than Max.age',
            'sfx.required'  => 'Maximum age requirement must be set',
            'pj.required'  => 'Education Level must be determined',
            'dir.required'  => 'Mimimum basic salary must be determined',
        ];
    }

    public function filters()
    {
        return [
        ];
    }

}
