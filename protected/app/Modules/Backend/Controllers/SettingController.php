<?php   namespace App\Modules\Backend\Controllers;



use App\Libs\Utils\Vii;
use App\Models\Config;
use App\Modules\Backend\Requests\SettingPostRequest;
use Illuminate\Http\Request;

class SettingController extends BaseController{

    private static $langName = 'setting';

    public function __construct(){
        parent::__construct();

        view()->share('lang_mod', $this->mod . '/' . self::$langName);

        $actions = request()->route()->getAction();
        $this->prefixUrl = $actions['prefix'];
    }
    
    public function getSettingForm(Request $request){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $setting = Config::where('tag_name', 'setting')->first();

        if($setting != null){
            return view(
                'Backend::setting.edit-setting',
                [
                    'qs' => $qs,
                    'prefix_url' => $this->prefixUrl,
                    'setting' => json_decode($setting->params)
                ]
            );
        }

        return view(
            'Backend::setting.create-setting',
            [
                'qs' => $qs,
                'prefix_url' => $this->prefixUrl,
            ]
        );
    }

    public function postCreateSetting(SettingPostRequest $request){

        $data = $request->only(['site_name', 'fb_id', 'ga_id', 'custom_analytics_script', 'default_seo_image', 'website_on_off', 'website_off_message', 'website_default_description', 'fb_page', 'fb_page_id']);

        $setting = Config::firstOrCreate(['tag_name'=>'setting']);

        $qs = Vii::queryStringBuilder($request->getQueryString());
        if($setting->update(['params'=>json_encode($data, JSON_UNESCAPED_UNICODE)])){
            return redirect(url($this->prefixUrl, []) . $qs)->with('message-success', trans($this->mod . '/' . self::$langName . '.setting_success'));
        }

        return redirect()->back()->with('message-error', trans($this->mod . '/' . self::$langName . '.setting_error'));



    }
}