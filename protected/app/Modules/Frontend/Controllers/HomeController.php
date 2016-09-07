<?php   namespace App\Modules\Frontend\Controllers;

use App\Libs\Utils\Vii;
use App\Models\Ads;
use App\Models\AdsPosition;
use App\Models\Category;
use App\Models\Config;
use App\Models\News;


use Illuminate\Http\Request;

use App\Models\Section;
use App\Models\Result;
use App\Models\ResultSetting;
use App\Models\OpenDay;
use Cache;
use Head;

class HomeController extends BaseController{

    private static $prefix_url = "/";

    private static $langName = 'home';

    public function __construct(){
        parent::__construct();

        //view()->share('left_blocks', $this->blocks->render('left'));
        $this->langMod = $this->mod . '/' . self::$langName;
        view()->share('lang_mod', $this->langMod);

        $this->pageTitle = 'Trang Chủ';
    }


    public function getHome(Request $request){

        $fields = [
            'news.id',
            'news.title',
            'news.alias',
            'news.intro_content',
            'news.featured_image',
            'news.published_at',
            'category.alias as cat_alias'
        ];

        $minutes = config('constant.MEMCACHE_TIMEOUT');

        //get highlight list
        $highlights = Cache::remember('home-news-highlight', $minutes, function() use($fields){
            return News::leftJoin('category', 'category.id', '=', 'news.cat_id')
                ->where('news.is_trashed', 0)
                ->where('news.status', config('constant.NEWS_PUBLISHED_STATUS'))
                ->where('news.is_highlight', 1)
                ->select($fields)
                ->orderBy('news.published_at', 'DESC')
                ->take(4)->get();
        });


        //get hot list
        $hots = Cache::remember('home-news-hot', $minutes, function() use($fields){
            return News::leftJoin('category', 'category.id', '=', 'news.cat_id')
                ->where('news.is_trashed', 0)
                ->where('news.status', config('constant.NEWS_PUBLISHED_STATUS'))
                ->where('news.is_hot', 1)
                ->select($fields)
                ->orderBy('news.published_at', 'DESC')
                ->take(15)->get();
        });

        $col_category = $this->_getCategoryList();

        $cat_group = 1;
        $ads_cfg = Config::where('tag_name', 'ads')->first();
        if($ads_cfg != null){
            $a = json_decode($ads_cfg->params, true);
            $cat_group = $a['cat_group'];
        }

        //category block list
        $position_data = Cache::remember('home-news-ads-position', $minutes, function(){
            return AdsPosition::getPositions(config('constant.PAGE_POSITION.HOME'));

        });

        $ads_positions = [];
        $key_pos = [];
        foreach($position_data as $k => $v){
            $ads_positions[$v->pos_name] = $v;
            $key_pos[] = $v->id;
        }

        //dd($ads_positions);

        $col_ads = Cache::remember('home-ads', $minutes, function() use($key_pos){
            return Ads::where('status', 1)
                ->where('page_id', config('constant.PAGE_POSITION.HOME'))
                ->whereIn('pos_id', $key_pos)
                ->where(function($q0){
                    $q0->where(function($q1){
                        $q1->whereNull('start_time')->whereNull('end_time');
                    })
                        ->orWhere(function($q2){
                            $now = date('Y-m-d H:i');
                            $q2->where('start_time', '<=', $now)->where('end_time' , '>=', $now);

                        });
                })
                ->select(['id', 'page_id', 'pos_id', 'ads_content', 'start_time', 'end_time', 'status'])
                ->orderBy('published_at', 'ASC')
                ->get();
        });


        //Set Head
        Head::setTitle($this->pageTitle);

        return view(
            'Frontend::pages.home',
            [
                //'page_title' => $this->pageTitle,
                'highlights' => $highlights,
                'hots' => $hots,
                'ads_positions' => $ads_positions,
                'col_ads' => $col_ads,
                'cat_group' => $cat_group,
                'col_category' => $col_category,
                'prefix_url' => $this->prefixUrl
            ]
        );
    }

    public function getSearchResult(Request $request){

        if($request->input('q', '') == ''){
            return redirect(route(config('frontend.ROUTE_NAME.home')));
        }

        $q = $request->input('q');
        $aq = explode(' ', $q);
        $sql = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
            ->leftJoin('news_detail', 'news.id', '=', 'news_detail.news_id')
            ->where('news.is_trashed', 0)
            ->where('news.status', config('constant.NEWS_PUBLISHED_STATUS'));

        $search_fields = ['news.title', 'news.intro_content', 'news_detail.main_content', 'news.tags'];
        $sql = Vii::makeSearchExactWords($sql, $search_fields, $q);

        $search_items = $sql->select(['news.id', 'news.title', 'news.featured_image', 'news.alias', 'news.intro_content', 'category.alias as cat_alias'])
            ->orderBy('published_at', 'DESC')
            ->paginate(config('frontend.TOTAL_ITEM_PER_PAGE_IN_SEARCH'));

        
        $search_items->appends(['q' => $request->input('q')]);

        //dd($search_items->toArray());

        return view(
            'Frontend::pages.search',
            [
                //'page_title' => $this->pageTitle,
                'search_items' => $search_items,
                'prefix_url' => $this->prefixUrl
            ]
        );

    }
    private function _getCategoryList(){

        $minutes = config('constant.MEMCACHE_TIMEOUT');

        $col_parent = Cache::remember('home-parent-cat-list', $minutes, function(){
            return Category::getFrontendParentList();
        });

        $col_children = Cache::remember('home-children-cat-list', $minutes, function(){
            return Category::getFrontendChildrenList();
        });

        foreach($col_parent as $k => &$parent){
            $children = $col_children->where('parent_id', $parent->id);
            $parent->children = $children;

            //Cache
            $col_news = Cache::remember('home-col-news-cat-'.$parent->id, $minutes, function() use($parent){
                return News::getArticleItemsByParentCategory($parent->id, config('frontend.TOTAL_ITEM_IN_CATE_BLOCK'));
            });

            $parent->news_items = $col_news;

        }

        return $col_parent;
    }


}