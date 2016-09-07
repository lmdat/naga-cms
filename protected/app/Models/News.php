<?php   namespace App\Models;

use App\Libs\Utils\Vii;
use Illuminate\Database\Eloquent\Model;


class News extends Model{

    protected $table = 'news';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'cat_id',
        'cat_parent_id',
        'title',
        'alias',
        'featured_image',
        'intro_content',
        'created_at',
        'modified_at',
        'approved_at',
        'published_at',
        'created_by',
        'modified_by',
        'approved_by',
        'published_by',
        'status',
        'ordering',
        'tags',
        'is_trashed',
        'is_highlight',
        'is_cat_highlight',
        'is_hot',
        'is_from_source',
        'url_source',
        'is_auto_published',
        'news_type'

    ];

    protected $guarded = [
        'id'
    ];

    public function created_by(){
        return $this->belongsTo('App\Models\Admin', 'created_by');
    }

    public function modified_by(){
        return $this->belongsTo('App\Models\Admin', 'modified_by');
    }

    public function category(){
        return $this->belongsTo('App\Models\Category', 'cat_id');
    }

    public function detail(){
        return $this->hasOne('App\Models\NewsDetail', 'news_id');
    }

    public function home_highlight(){
        return $this->hasOne('App\Models\NewsHomeHighlight', 'news_id');
    }

    public function cat_highlight(){
        return $this->hasOne('App\Models\NewsCatHighlight', 'news_id');
    }

    public function auto_publish(){
        return $this->hasOne('App\Models\NewsAutoPublish', 'news_id');
    }

    public function hit_counter(){
        return $this->hasOne('App\Models\NewsHitCounter', 'news_id');
    }

    public function news_relation(){
        return $this->hasOne('App\Models\NewsRelation', 'news_id');
    }

    public function meta_data(){
        return $this->hasOne('App\Models\NewsMeta', 'news_id');
    }

    public function images(){
        return $this->hasMany('App\Models\FileUploader', 'news_id');
    }


    /*---------------------BACKEND---------------------*/
    public static function getNewsList($fields, $trashed=0, $is_where=null, $type=0, $cat_id='', $q='', $paging=20){

        $news_sql = News::leftJoin('category', 'news.cat_id', '=', 'category.id')
            //->leftJoin('admin', 'news.created_by', '=', 'admin.id')
            //->leftJoin('admin', 'news.modified_by', '=', 'admin.id')
            ->where('news.is_trashed', $trashed);

        if($type > 0){
            $news_sql->where('news.news_type', $type);
        }

        if($cat_id != ''){
            $news_sql->where('news.cat_id', $cat_id);
        }

        if($q != ''){
            $news_sql = Vii::makeSearchExactWords($news_sql, ['news.title', 'news.tags', 'news.intro_content'], $q);
        }

        if($is_where != null && is_array($is_where) && !empty($is_where)){
            foreach($is_where as $k => $v){
                $news_sql->where($k, $v[0], $v[1]);
                
            }
        }


        return $news_sql->select($fields)->orderBy('created_at', 'DESC')->paginate($paging);

    }

    public function parseFeaturedImageUrl(){

        if($this->featured_image != ''){
            $url_apart = parse_url($this->featured_image);
            if(!(isset($url_apart['scheme']) && isset($url_apart['host']))) {

                $parts = explode('@', $this->featured_image);
                $img_id = @$parts[0];

                $featured = FileUploader::find($img_id);
                return $this->_getFeaturedImageUri($featured, @$parts[1]);
            }
        }

        return $this->featured_image;

    }

    private function _getFeaturedImageUri($featured, $img){
        if($featured == null)
            return "";

        if($featured->cdn == ''){
            return config('app.url') . '/' . $featured->root_url . '/' . $img;
        }

        return $featured->cdn . '/' . $featured->root_url . '/'. $img;

    }

    /*---------------------FRONTEND---------------------*/

    public static function getArticleByParentCategory($parent_cat_id, $fields=null){

        if($fields == null){
            $fields = [
                'news.id',
                'news.title',
                'news.alias',
                'news.intro_content',
                'news.featured_image',
                'news.published_at',
                'category.alias AS cat_alias'
            ];
        }

        $list = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
            ->where('status', config('constant.NEWS_PUBLISHED_STATUS'))
            ->where('category.parent_id', $parent_cat_id)
            ->where('category.published', 1)
            ->where('category.is_trashed', 0)
            ->select($fields)
            ->orderBy('published_at', 'DESC')
            ->first();

        return $list;
    }

    public static function getArticleByCategory($cat_id, $fields=null){

        if($fields == null){
            $fields = [
                'news.id',
                'news.title',
                'news.alias',
                'news.intro_content',
                'news.featured_image',
                'news.published_at',
                'category.alias AS cat_alias'
            ];
        }

        $list = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
            ->where('status', config('constant.NEWS_PUBLISHED_STATUS'))
            ->where('category.id', $cat_id)
            ->where('category.published', 1)
            ->where('category.is_trashed', 0)
            ->select($fields)
            ->orderBy('published_at', 'DESC')
            ->first();

        return $list;
    }

    public static function getArticleItemsByParentCategory($parent_cat_id, $take=4, $fields=null){

        if($fields == null){
            $fields = [
                'news.id',
                'news.title',
                'news.alias',
                'news.intro_content',
                'news.featured_image',
                'news.published_at',
                'category.alias AS cat_alias'
            ];
        }

        $list = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
            ->where('status', config('constant.NEWS_PUBLISHED_STATUS'))
            ->where('category.parent_id', $parent_cat_id)
            ->where('category.published', 1)
            ->where('category.is_trashed', 0)
            ->select($fields)
            ->orderBy('published_at', 'DESC')
            ->paginate($take);

        return $list;
    }

    public static function getArticleItemsByCategory($cat_id, $take=4, $fields=null){

        if($fields == null){
            $fields = [
                'news.id',
                'news.title',
                'news.alias',
                'news.intro_content',
                'news.featured_image',
                'news.published_at',
                'category.alias AS cat_alias'
            ];
        }

        $list = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
            ->where('status', config('constant.NEWS_PUBLISHED_STATUS'))
            ->where('category.id', $cat_id)
            ->where('category.published', 1)
            ->where('category.is_trashed', 0)
            ->select($fields)
            ->orderBy('published_at', 'DESC')
            ->paginate($take);

        return $list;
    }

    public static function getMostReadArticleItems($article, $take=15, $fields=null){
        if($fields == null){
            $fields = [
                'news.id',
                'news.title',
                'news.alias',
                'news.intro_content',
                'news.featured_image',
                'category.alias as cat_alias',
                'news_hit_counter.hit_counter'
            ];
        }

        $sql = News::leftJoin('category', 'category.id', '=', 'news.cat_id')
            ->leftJoin('news_hit_counter', 'news_hit_counter.news_id', '=', 'news.id')
            ->where('news.status', config('constant.NEWS_PUBLISHED_STATUS'))
            ->where('news.is_trashed', 0);
        if($article != null)
            $sql->where('news.id', '!=', $article->id);

        $list = $sql->select($fields)
            ->orderBy('news_hit_counter.hit_counter', 'DESC')
            ->take($take)
            ->get();

        return $list;
    }

}
