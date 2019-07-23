<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequestForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'empid' => 'required',
            'fromdate' => 'required|before_or_equal:todate',
            'todate' => 'required|after_or_equal:fromdate',
            'leavetype' => 'required',
            'reason' => 'requiredIf:rs,true',
        ];
    }

    public function messages()
    {
        return [
            'empid.required' => 'Critical Error, please contact Webmaster',
            'todate.after_or_equal' => 'จนถึงวันที่ต้องมากกว่าจากวันที่',
            'reason.requiredIf'  => 'ต้องระบุเหตุผล (Reason is required)',
        ];
    }


}
