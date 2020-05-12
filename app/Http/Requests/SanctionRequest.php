<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SanctionRequest extends FormRequest
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
            //
            "decision"=>'required',
            "user_id"=>'integer|required',
            "days"=>'integer|required',
            "raison"=>'string',
            "start_date"=>'date'
        ];
    }
}
