<?php   namespace App\Modules\Frontend\Controllers;




use App\Models\Ads;
use App\Models\AdsPosition;
use App\Models\Category;
use App\Models\News;
use Illuminate\Http\Request;
use Cache;
use Head;

class CategoryController extends BaseController{

    private static $prefix_url = "/";

    private static $langName = 'category';

    public function __construct(){
        parent::__construct();

        //view()->share('left_blocks', $this->blocks->render('left'));
        
        $this->langMod = $this->mod . '/' . self::$langName;
        view()->share('lang_mod', $this->langMod);

        $this->pageTitle = '';
    }

    public function getCategory(Request $request, $alias=null){

        $cat = Category::where('alias', ltrim(rtrim($alias)))->first();

        if($cat == null){
            abort(404, trans($this->langError . '.url_not_found'));
        }


        $this->pageTitle = $cat->cat_name;

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

        $cache_prefix_name = $cat->alias .'-'. $cat->id;

        //Get Category List
        $cat_siblings = collect([]);
        if($cat->parent_id == 0){
            $cat_siblings = Category::getChildrenListFromParent($cat->id);
        }
        else{
            $cat_siblings = Category::getSiblingList($cat->id, $cat->parent_id);
        }


        //get highlight list
        $highlight = Cache::remember('cat-news-highlight-'.$cache_prefix_name, $minutes, function() use($fields, $cat){
            $col_hl = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
                ->with('cat_highlight')
                ->where('news.is_trashed', 0)
                ->where('news.status', config('constant.NEWS_PUBLISHED_STATUS'))
                ->where('news.is_cat_highlight', 1)
                ->where('category.published', 1);

            if($cat->parent_id > 0){
                $col_hl->where('category.id', $cat->id);
            }
            else{
                $col_hl->where('category.parent_id', $cat->id);
            }

            return $col_hl->select($fields)
                ->orderBy('news.published_at', 'DESC')
                ->first();
        });

        if($highlight == null){
            if($cat->parent_id > 0){
                $highlight = News::getArticleByCategory($cat->id);
            }
            else{
                $highlight = News::getArticleByParentCategory($cat->id);
            }
        }


//        $col_news = Cache::remember('cat-news-list-'.$cache_prefix_name, $minutes, function() use($cat){
//            if($cat->parent_id > 0)
//                return News::getArticleItemsByCategory($cat->id, config('frontend.TOTAL_ITEM_PER_PAGE_IN_CATEGORY'));
//            return News::getArticleItemsByParentCategory($cat->id, config('frontend.TOTAL_ITEM_PER_PAGE_IN_CATEGORY'));
//        });

        $col_news = collect([]);
        if($cat->parent_id > 0){
            $col_news = News::getArticleItemsByCategory($cat->id, config('frontend.TOTAL_ITEM_PER_PAGE_IN_CATEGORY'));
        }
        else{
            $col_news = News::getArticleItemsByParentCategory($cat->id, config('frontend.TOTAL_ITEM_PER_PAGE_IN_CATEGORY'));
        }


        $hl_relations = collect([]);
        if($highlight != null){
            $relation_ids = $highlight->news_relation()->select(['relation_ids'])->first();
            if($relation_ids != ""){
                $a = explode(',', $relation_ids);
                $hl_relations = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
                    ->whereIn('news.id', $a)
                    ->select(['news.id', 'news.title', 'news.alias', 'category.alias as cat_alias'])
                    ->orderBy('news.published_at', 'DESC')
                    ->get();

            }
        }

        //Ads position
        $position_data = Cache::remember('cat-news-ads-position-'.$cache_prefix_name, $minutes, function(){
            return AdsPosition::getPositions(config('constant.PAGE_POSITION.CATEGORY'));

        });

        $ads_positions = [];
        $key_pos = [];
        foreach($position_data as $k => $v){
            $ads_positions[$v->pos_name] = $v;
            $key_pos[] = $v->id;
        }

        $col_ads = Cache::remember('cat-ads-'.$cache_prefix_name, $minutes, function() use($key_pos){
            return Ads::where('status', 1)
                ->where('page_id', config('constant.PAGE_POSITION.CATEGORY'))
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
            'Frontend::pages.category',
            [
                'cat' => $cat,
                'cat_siblings' => $cat_siblings,
                'highlight' => $highlight,
                'hl_relations' => $hl_relations,
                'col_news' => $col_news,
                'ads_positions' => $ads_positions,
                'col_ads' => $col_ads,
                'most_read_list' => $this->_getMostReadList(),
                'prefix_url' => $this->prefixUrl
            ]
        );
    }

    private function _getMostReadList(){
        $minutes = config('constant.MEMCACHE_TIMEOUT');

        $list = Cache::remember('cat-most-read-list', $minutes, function(){

            return News::getMostReadArticleItems(null, config('frontend.TOTAL_ITEM_IN_MOST_READ_BLOCK'));
        });

        return $list;
    }
}