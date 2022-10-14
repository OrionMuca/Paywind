<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterFormRequest extends FormRequest
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
            //dd($this);
            return [
                'first_name'=>'required|min:3|alpha',
                'last_name'=>'required|min:3|alpha',
                'fatherhood'=>'required|min:3|alpha',
                'username'=>'required',
                'phone'=>'required|digits:6|unique:users',
                'email'=>'required|email|unique:users',
                'birth_date'=>'required|date_format:Y-m-d',
                'password'=>'required|min:6|regex:^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[!$#%]).*$^',
                'repeat_password'=>'required|same:password',
            ];
        }
}
