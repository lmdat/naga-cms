<?php   namespace App\Modules\Frontend\Partials;

use App\Models\News;
use Cache;

class MostReadPartial extends BasePartial{

    

    public static function render($params=[]){



        $v = view("Frontend::partials.most-read",
            [
                'most_read_list' => self::_getMostReadList(),

            ]
        );

        return $v->render();
    }

    private static function _getMostReadList(){
        $minutes = config('constant.MEMCACHE_TIMEOUT');


        $list = Cache::remember('most-read-list', $minutes, function(){

            return News::getMostReadArticleItems(null, config('frontend.TOTAL_ITEM_IN_MOST_READ_BLOCK'));
        });

        return $list;
    }


}