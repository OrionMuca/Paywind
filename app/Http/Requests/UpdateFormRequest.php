<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateFormRequest extends FormRequest
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
        //dd($this->id);
        return [
            'first_name'=>'required|min:3|alpha',
            'last_name'=>'required|min:3|alpha',
            'fatherhood'=>'required|min:3|alpha',
            'username'=>'required',
            //'phone'=>'required|digits:6|unique:users,phone,'.$this->id,
            'email'=>'required|email|unique:users,email,'.$this->id,
            'birth_date'=>'required|date_format:Y-m-d',
            'id'
        ];
    }
}
