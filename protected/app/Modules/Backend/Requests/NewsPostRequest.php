<?php namespace App\Modules\Backend\Requests;

use App\Modules\Backend\Requests\Request;

use Auth;

class NewsPostRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    protected $langName;

    public function __construct(){
        $this->langName = strtolower(config('module.modules.admin.folder_name')) . '/news';
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
            'cat_id' => 'required',
            'title' => 'required',
            //'featured_image' => 'required',
            //'intro_content' => 'required',
            'main_content' => 'required'
        ];
    }

    public function messages(){
        return [
            'cat_id.required' => trans($this->langName . '.cat_id_validate.required', ['field' => trans($this->langName . '.cat_id')]),
            'title.required' => trans($this->langName . '.title_validate.required', ['field' => trans($this->langName . '.title')]),
            //'featured_image.required' => trans($this->langName . '.featured_image_validate.required', ['field' => trans($this->langName . '.featured_image')]),
            //'intro_content.required' => trans($this->langName . '.intro_content_validate.required', ['field' => trans($this->langName . '.intro_content')]),
            'main_content.required' => trans($this->langName . '.main_content_validate.required', ['field' => trans($this->langName . '.main_content')]),
        ];
    }
}
