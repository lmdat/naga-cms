<?php   namespace App\Modules\Frontend\Partials;

use App\Models\Ads;
use App\Models\AdsPosition;
use Route;

class HeaderPartial extends BasePartial{

   
    public static function render($params=[]){

        $routeName = Route::currentRouteName();

        $page_name = '';
        foreach(config('frontend.ROUTE_NAME') as $k => $v){
            if($routeName == $v){
                $page_name = $k;
                break;
            }
        }


        $page_id = 0;
        switch($routeName){
            case config('frontend.ROUTE_NAME.home'):
                $page_id = config('constant.PAGE_POSITION.HOME');
                break;
            case config('frontend.ROUTE_NAME.category'):
                $page_id = config('constant.PAGE_POSITION.CATEGORY');
                break;
            case config('frontend.ROUTE_NAME.detail_news'):
            case config('frontend.ROUTE_NAME.detail_video'):
                $page_id = config('constant.PAGE_POSITION.DETAIL');
                break;
        }

        //Ads Position
        $position_data = AdsPosition::where('page_id', $page_id)
            ->where('is_trashed', 0)
            ->where('is_fix', 1)
            ->where('status', 1)
            ->select(['id', 'pos_name', 'width', 'height', 'is_fix'])
            ->get();


        $ads_positions = [];
        foreach($position_data as $k => $v){
            if(strpos($v->pos_name, 'mainmenu') !== false)
                $ads_positions[$v->pos_name] = $v;
        }

        
        //Ads
        $col_ads = Ads::leftJoin('ads_position', 'ads_position.id', '=', 'ads.pos_id')
            ->where('ads.status', 1)
            ->where('ads.page_id', $page_id)
            ->where(function($q0){
                $q0->where(function($q1){
                    $q1->whereNull('ads.start_time')->whereNull('ads.end_time');
                })
                ->orWhere(function($q2){
                    $now = date('Y-m-d H:i');
                    $q2->where('ads.start_time', '<=', $now)->where('ads.end_time' , '>=', $now);

                });
            })
            ->select(['ads.id', 'ads.page_id', 'ads.pos_id', 'ads.ads_content', 'ads.start_time', 'ads.end_time', 'ads.status', 'ads_position.pos_name'])
            ->orderBy('ads.published_at', 'ASC')
            ->get();

        //dd($col_ads->toArray());


        $v = view("Frontend::partials.sticky-header",
            [
                'col_ads' => $col_ads,
                'page_name' => strtoupper($page_name),
                'ads_positions' => $ads_positions,
                'right_above_menu' => config('constant.ADS_POSITION.' . strtoupper($page_name). '_FIX.' . strtoupper($page_name) . '_RIGHT_ABOVE_MAINMENU'),
                'full_below_menu' => config('constant.ADS_POSITION.' . strtoupper($page_name). '_FIX.' . strtoupper($page_name) . '_FULL_BELOW_MAINMENU'),

            ]
        );

        return $v->render();

    }
}