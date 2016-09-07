<?php namespace App\Modules\Backend\Requests;

use App\Modules\Backend\Requests\Request;

use Auth;

class CategoryPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected $langName;

    public function __construct(){
        $this->langName = strtolower(config('module.modules.admin.folder_name')) . '/category';
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
            'cat_name' => 'required',
            'alias' => 'unique:category,alias,' . $this->id
        ];
    }

    public function messages(){
        return [
            'cat_name.required' => trans($this->langName . '.cat_name_validate.required', ['field' => trans($this->langName . '.cat_name')]),
            'alias.unique' => trans($this->langName . '.alias_validate.unique', ['field' => trans($this->langName . '.alias')]),
        ];
    }
}
