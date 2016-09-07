<?php   namespace App\Modules\Backend\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;

use Auth;

class BaseController extends Controller{

    use DispatchesJobs, ValidatesRequests, SidebarMenuTrait;

    protected $guard;

    protected $guardName;

    protected $sidebarMenu;

    protected $mod;

    protected $prefixUrl;


    public function __construct(){
        $this->middleware('admin_auth');

        app()->setLocale(config('backend.lang'));


        $this->init();

        view()->share('assets', config('backend.media'));
        view()->share('sidebar_menu', $this->sidebarMenu);

        $start = config('backend.start_year');
        $copy_year = (date('Y') == $start) ? $start : $start . '-' . date('Y');
        view()->share('copy_right_year', $copy_year);

        view()->share('full_name', $this->guard->user()->first_name . ' ' . $this->guard->user()->surname);
        view()->share('user_role', $this->guard->user()->getMaxRoleName());

        view()->share('lang_common', $this->mod . '/common');


    }

    protected function init(){
        $this->sidebarMenu = $this->createMenu();
        $this->guardName = 'admin';
        $this->guard = Auth::guard($this->guardName);

        $this->mod = strtolower(config('module.modules.admin.folder_name'));
        $this->prefixUrl = '';

    }

}