<?php namespace App\Modules\Backend\Requests;

use App\Modules\Backend\Requests\Request;

use Auth;

class AdsPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected $langName;

    public function __construct(){
        $this->langName = strtolower(config('module.modules.admin.folder_name')) . '/ads';
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
            'pos_id' => 'required',
            'ads_title' => 'required',
            'ads_content' => 'required',
        ];
    }

    public function messages(){
        return [
            'pos_id.required' => trans($this->langName . '.pos_id_validate.required', ['field' => trans($this->langName . '.pos_id')]),
            'ads_title.required' => trans($this->langName . '.ads_title_validate.required', ['field' => trans($this->langName . '.ads_title')]),
            'ads_content.required' => trans($this->langName . '.ads_content_validate.required', ['field' => trans($this->langName . '.ads_content')]),
        ];
    }
}
