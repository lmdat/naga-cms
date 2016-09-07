<?php namespace App\Modules\Backend\Requests;

use App\Modules\Backend\Requests\Request;

use Auth;

class SettingPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected $langName;

    public function __construct(){
        $this->langName = strtolower(config('module.modules.admin.folder_name')) . '/setting';
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
            'site_name' => 'required',
            'fb_id' => 'numeric',
            'fb_page' => 'url',
            'fb_page_id' => 'numeric',
            //'ga_id' => 'required',
            'default_seo_image' => 'sometimes|image'
        ];
    }

    public function messages(){
        return [
            'site_name.required' => trans($this->langName . '.site_name_validate.required', ['field' => trans($this->langName . '.site_name')]),
//            'fb_id.required' => trans($this->langName . '.fb_id_validate.required', ['field' => trans($this->langName . '.fb_id')]),
//            'fb_id.numeric' => trans($this->langName . '.fb_id_validate.numeric', ['field' => trans($this->langName . '.fb_id')]),
//            'ga_id.required' => trans($this->langName . '.ga_id_validate.required', ['field' => trans($this->langName . '.ga_id')]),
            'ga_id.numeric' => trans($this->langName . '.ga_id_validate.numeric', ['field' => trans($this->langName . '.ga_id')]),
            'default_seo_image.image' => trans($this->langName . '.default_seo_image_validate.image', ['field' => trans($this->langName . '.default_seo_image')]),
            'fb_page.url' => trans($this->langName . '.fb_page_validate.url', ['field' => trans($this->langName . '.fb_page')]),
            'fb_page_id.numeric' => trans($this->langName . '.fb_page_id_validate.numeric', ['field' => trans($this->langName . '.fb_page_id')]),
        ];
    }
}
