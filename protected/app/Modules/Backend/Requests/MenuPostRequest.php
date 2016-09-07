<?php namespace App\Modules\Backend\Requests;

use App\Modules\Backend\Requests\Request;

use Auth;

class MenuPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected $langName;

    public function __construct(){
        $this->langName = strtolower(config('module.modules.admin.folder_name')) . '/menu';
    }

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
            'menu_title' => 'required',
            'menu_pos' => 'required',
        ];
    }

    public function messages(){
        return [
            'menu_title.required' => trans($this->langName . '.menu_title_validate.required', ['field' => trans($this->langName . '.menu_title')]),
            'menu_pos.required' => trans($this->langName . '.menu_pos_validate.required', ['field' => trans($this->langName . '.menu_pos')]),
        ];
    }
}
