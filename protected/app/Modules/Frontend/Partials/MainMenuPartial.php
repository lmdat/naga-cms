<?php   namespace App\Modules\Frontend\Partials;

use Cache;
use App\Models\Menu;

class MainMenuPartial extends BasePartial{



    public static function render($params=[]){

        $minutes = config('constant.TOP_MENU_MEMCACHE_TIMEOUT');

        $menu = Cache::remember('top-menu', $minutes, function(){

            return Menu::where('menu_pos', 'menu-top')
                ->first()->menu_cache()->first();
        });

        $main_menu = json_decode($menu->json_data, true);
        //dd($main_menu);
        $rs = self::_createMenu($main_menu);


        $root_url = config('app.url');
        if($root_url == null)
            $root_url = url('/');

        $v = view("Frontend::partials.main-menu",
            [
                'menu_data' => $rs,
                'root_url' => $root_url
            ]
        );


        return $v->render();
    }

    private static function _createMenu($menu=[]){
        $html = '';


        if(!empty($menu)){

            foreach($menu as $k => $item){

                $pa = '';
                $child = self::_childMenu($item['item_children'], $pa, 1);
                $url = '';
                if($item['cat_id'] != 0){
                    $url = $item['alias'];
                }

                else{
                    $url = $item['custom_url'];
                }

                $_active = '';
                if($item['cat_id'] != 0){
                    if(request()->segment(1) == $item['alias']){
                        $_active = 'active';
                    }

                }
                else {
                    if(request()->url() == $item['custom_url']){
                        $_active = 'active';
                    }
                }

                if($child == ''){

                    $html .= "<li class='$_active'>";
                    $html .= "<a href='".url($url) . "'>";
                    $html .= $item['item_name'];
                    $html .= "</a>";
                    $html .= "</li>";
                }
                else{
                    if($pa == '')
                        $pa = $_active;
                    
                    $html .= "<li class='dropdown $pa'>";
                    $html .= "<a href='".url($url) . "'>";
                    $html .= $item['item_name'];
                    $html .= "</a>";
                    $html .= $child;
                    $html .= "</li>";
                }
            }
        }

        return $html;

    }

    private static function _childMenu($children=[], &$parent_active='', $h=1){
        if(empty($children))
            return '';

        $html = '';
        foreach($children as $k => $item){


            //$_active = '';
            if($item['cat_id'] != 0){
                if(request()->segment(1) == $item['alias']){
                    //$_active = 'active';
                    $parent_active = 'active';
                }

            }
            else{
                if(request()->url() == $item['custom_url']){
                    $parent_active = 'active';
                }
            }

            $url = '';
            if($item['cat_id'] != 0){
                $url = $item['alias'];
            }

            else{
                $url = $item['custom_url'];
            }

            $pa = '';
            $html .= "<li>";
            $html .= "<a href='".url($url) . "'>";
            $html .= $item['item_name'];

            $html .= "</a>";
            $html .= self::_childMenu($item['item_children'], $pa, $h + 1);
            $html .= "</li>";
        }

        return "<ul class='dropdown-menu'>$html</ul>";
    }
}