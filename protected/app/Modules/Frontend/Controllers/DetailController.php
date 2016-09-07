<?php   namespace App\Modules\Frontend\Controllers;


use App\Events\ArticleCounterEvent;
use App\Libs\Utils\Vii;
use App\Models\Ads;
use App\Models\AdsPosition;
use App\Models\Category;
use App\Models\Config;
use App\Models\News;
use App\Models\NewsHitCounter;
use Illuminate\Http\Request;

use Event;
use Cache;
use Head;

class DetailController extends BaseController{

    private static $langName = 'detail';

    public function __construct(){
        parent::__construct();

        //view()->share('left_blocks', $this->blocks->render('left'));
        $this->langMod = $this->mod . '/' . self::$langName;
        view()->share('lang_mod', $this->langMod);

        $this->pageTitle = '';
    }

    public function getNewsDetail(Request $request, $cat_alias=null, $alias=null, $id=null){

        if($alias == null || $id == null){
            abort(404, trans($this->langError . '.url_not_found'));
        }

        $fields = [
            'news.id',
            'news.title',
            'news.tags',
            'news.intro_content',
            'news.featured_image',
            'news.published_at',
            'category.cat_name'
        ];

        $minutes = config('constant.MEMCACHE_TIMEOUT');

        //Breadcrumbs
        $cat = Category::where('alias', ltrim(rtrim($cat_alias)))->select(['id', 'parent_id', 'cat_name', 'alias'])->first();
        $breadcrumbs = collect([]);
        if($cat != null){
            $parent = $cat->parent()->select(['id', 'parent_id', 'cat_name', 'alias'])->first();
            $breadcrumbs = collect([$parent, $cat]);
        }



        $cache_prefix_name = $cat_alias . '-' . $alias .'-' .$id;
        $article = Cache::remember('detail-article-'.$cache_prefix_name, $minutes, function() use($id, $cat_alias, $alias, $fields){
            $item = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
                ->with('detail')
                ->with('meta_data')
                ->where('news.alias', ltrim(rtrim($alias)))
                ->where('news.status', config('constant.NEWS_PUBLISHED_STATUS'))
                ->where('news.is_trashed', 0)
                ->where('category.alias', ltrim(rtrim($cat_alias)))
                ->where('category.published', 1)
                ->select($fields)
                ->find($id);

            if($item == null)
                abort(404, trans($this->langError . '.url_not_found'));


            return $item;
        });


        //dd($news->toArray());

        $article_relations = collect([]);
        if($article != null){
            $relation_ids = $article->news_relation()->select(['relation_ids'])->first();
            if($relation_ids != ""){
                $a = explode(',', $relation_ids);
                $article_relations = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
                    ->whereIn('news.id', $a)
                    ->select(['news.id', 'news.title', 'news.alias', 'category.alias as cat_alias'])
                    ->orderBy('news.published_at', 'DESC')
                    ->get();

            }
        }

        //Tags
        $tags = [];
        if($article->tags != ''){
            $tags = explode(',', $article->tags);
        }

        //Ads position
        $position_data = Cache::remember('article-news-ads-position', $minutes, function(){
            return AdsPosition::getPositions(config('constant.PAGE_POSITION.DETAIL'));

        });

        $ads_positions = [];
        $key_pos = [];
        foreach($position_data as $k => $v){
            $ads_positions[$v->pos_name] = $v;
            $key_pos[] = $v->id;
        }

        $col_ads = Cache::remember('article-ads', $minutes, function() use($key_pos){
            return Ads::where('status', 1)
                ->where('page_id', config('constant.PAGE_POSITION.DETAIL'))
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
        $this->_setArticleHead($article, $request);

        $this->_setHitCounter($article, $request);

        $article_datetime = Vii::getDayName(date('w', strtotime($article->published_at))) . ', ';
        $article_datetime .= Vii::formatDateTime($article->published_at, 'd/m/Y H:i');
        $article_datetime .= ' GMT' . (date('P'));

        return view(
            'Frontend::pages.news-detail',
            [
                'breadcrumbs' => $breadcrumbs,
                'article' => $article,
                'tags' => $tags,
                'article_relations' => $article_relations,
                'ads_positions' => $ads_positions,
                'col_ads' => $col_ads,
                'most_read_list' => $this->_getMostReadList($article),
                'article_datetime' => $article_datetime,
                'prefix_url' => $this->prefixUrl
            ]
        );
    }

    private function _getMostReadList($article){
        $minutes = config('constant.MEMCACHE_TIMEOUT');

        $list = Cache::remember('detail-most-read-list', $minutes, function() use($article){

            return News::getMostReadArticleItems($article, config('frontend.TOTAL_ITEM_IN_MOST_READ_BLOCK'));
        });


        return $list;
    }

    private function _setHitCounter($article, $request){
        //NewsHitCounter::where('news_id', $article->id)->increment('hit_counter');
        //$article->hit_counter()->first()->increment('hit_counter');
        $counter = NewsHitCounter::firstOrCreate(['news_id'=>$article->id]);
        Event::fire(new ArticleCounterEvent($counter));

    }

    private function _setArticleHead($article, $request){
        Head::setTitle($article->meta_data->meta_title != '' ? htmlentities($article->meta_data->meta_title) : htmlentities($article->title));
        Head::setDescription($article->meta_data->meta_description != '' ? htmlentities($article->meta_data->meta_description) : htmlentities($article->title));
        Head::addOneMeta('name', 'keywords', $article->meta_data->meta_keywords != '' ? htmlentities($article->meta_data->meta_keywords) : htmlentities($article->tags));


        //Facebook
        if($this->setting == null){
            Head::noFacebook();
        }
        else{
            if($this->setting->fb_id == ''){
                Head::noFacebook();
            }
            else{
                Head::doFacebook();
            }
        }

        
        Head::addOneMeta('property', 'fb:app_id', @$this->setting->fb_id);
        Head::addOneMeta('property', 'og:site_name', @$this->setting->site_name);
        Head::addOneMeta('property', 'article:publisher', @$this->setting->fb_page);
        Head::addOneMeta('property', 'article:author', @$this->setting->fb_page);
        Head::addOneMeta('property', 'fb:pages', @$this->setting->fb_page_id);


        Head::addOneMeta('property', 'og:type', 'article');
        Head::addOneMeta('property', 'article:section', $article->cat_name);
        Head::addOneMeta('property', 'og:title', $article->meta_data->og_title != '' ? htmlentities($article->meta_data->og_title) : htmlentities($article->title));
        Head::addOneMeta('property', 'og:url', $request->url());
        Head::addOneMeta('property', 'og:description', $article->meta_data->og_description != '' ? htmlentities($article->meta_data->og_description) : htmlentities($article->title));
        Head::addOneMeta('property', 'og:image', $article->meta_data->og_image != '' ? $article->meta_data->og_image : $article->parseFeaturedImageUrl());
        //Head::addOneMeta('property', 'og:image:width', '');
        //Head::addOneMeta('property', 'og:image:height', '');



    }
}