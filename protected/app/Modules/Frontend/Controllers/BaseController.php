<?php   namespace App\Modules\Frontend\Controllers;

use App\Models\Config;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

use Auth;


class BaseController extends Controller{
    
    use DispatchesJobs, ValidatesRequests;
    
            
    protected $sidebarMenu;

    protected $guard;

    protected $guard_name;

    protected $blocks;

    protected $pageTitle;

    protected $prefixUrl;

    protected $mod;

    protected $setting;

    protected $langError;

    protected $langMod;

    protected $langCommon;
    
    public function __construct(){
        
        //$this->middleware('admin_auth');

        app()->setLocale(config('frontend.lang'));
        
        $this->init();
        view()->share('assets', config('frontend.media'));
        //view()->share('sidebar_menu', $this->sidebarMenu);

        
        $start = config('frontend.start_year');
        $copy_year = (date('Y') == $start) ? $start : $start . '-' . date('Y');
        view()->share('copy_right_year', $copy_year);

        view()->share('lang_error', $this->langError);
        view()->share('lang_common', $this->langCommon);

    }
    
    protected function init(){
        //$this->sidebarMenu = $this->createMenu();
        //$this->guard_name = 'user';
        //$this->guard = Auth::guard($this->guard_name);
        $this->pageTitle = '';
        $this->prefixUrl = '';
        $this->mod = strtolower(config('module.modules.site.folder_name'));

        $this->langError = $this->mod . '/error';
        $this->langCommon= $this->mod . '/common';

        $this->loadSetting();
    }

    protected function loadSetting(){
        $config = Config::where('tag_name', 'setting')->first();

        $this->setting = null;
        if($config != null)
            $this->setting = json_decode($config->params);

        //Google Analytics
        config(['laravel-head.analytics.id' => @$this->setting->ga_id]);
    }
}