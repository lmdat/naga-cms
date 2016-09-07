<?php   namespace App\Modules;

use Illuminate\Support\ServiceProvider;

use Request;
use Theme;

class ModuleServiceProvider extends ServiceProvider{

    public function register()
    {
        // TODO: Implement register() method.
    }

    public function boot(){
        $modules = config('module.modules');

        $mod = $modules['site']['folder_name'];
        $base_url = request()->getBaseUrl();

        $backend_slug = $modules['admin']['slug_name'];
        //base url end with cms or cms/
        $pattern = '/(\/'.$backend_slug.'|\/'.$backend_slug.'\/)$/';
        if(preg_match($pattern, $base_url, $match)){
        //if(Request::is('admin') || Request::is('admin/*')){
            $mod = $modules['admin']['folder_name'];

        }

        if(file_exists(__DIR__ . '/' . $mod . '/routes.php')){
            include __DIR__ . '/' . $mod . '/routes.php';
        }

        if(is_dir(__DIR__ . '/' . $mod . '/Views')){

            if($mod == $modules['admin']['folder_name']){
                $this->loadViewsFrom(__DIR__ . '/' . $mod . '/Views', $mod);
            }
            else{
                $this->loadViewsFrom(__DIR__ . '/' . $mod . '/Views/themes/' . Theme::get(), $mod);
            }

        }



    }

}