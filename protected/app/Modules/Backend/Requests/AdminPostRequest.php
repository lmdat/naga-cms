<?php namespace App\Modules\Backend\Requests;

use App\Modules\Backend\Requests\Request;

use Auth;

class AdminPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //return Auth::admin()->check();
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
            'first_name' => 'required',
            'surname' => 'required',
            'email' => 'required|email|unique:admin,email',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required'
            //'total_prize' => 'required|integer|min:1',
        ];
    }
    
    public function messages(){
        return [
            'first_name.required' => '[First Name] cannot be blank.',
            'surname.required' => '[Surname] cannot be blank',
            'email.required' => '[Email] cannot be blank',
            'email.email' => '[Email] must be an email',
            'email.unique' => '[Email] has been used already.',
            'password.required' => '[Password] cannot be blank.',
            'password.min' => '[Password] length must be >= 6.',
            'roles.required' => '[Role] select at least one Role.',
        ];
    }
}
