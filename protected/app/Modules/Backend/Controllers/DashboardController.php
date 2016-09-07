<?php   namespace App\Modules\Backend\Controllers;



class DashboardController extends BaseController{

    private static $langName = 'dashboard';

    public function __construct(){
        parent::__construct();

        view()->share('lang_mod', $this->mod . '/' . self::$langName);

        $actions = request()->route()->getAction();
        $this->prefixUrl = $actions['prefix'];
    }
    
    public function welcome(){


        return view(
            'Backend::dashboard.index',
            [
                'user' => session()->get('test-name', 'Unknown')
            ]
        );
    }
}