<?php   namespace App\Modules\Backend\Controllers;

use App\Libs\Utils\FTPConnection;
use App\Libs\Utils\Vii;
use App\Models\FileUploader;
use App\Models\NewsAutoPublish;
use App\Models\NewsCatHighlight;
use App\Models\NewsDetail;
use App\Models\NewsHitCounter;
use App\Models\NewsHomeHighlight;
use App\Models\NewsMeta;
use App\Models\NewsRelation;
use App\Modules\Backend\Requests\NewsPostRequest;
use Illuminate\Http\Request;


use App\Models\News;
use App\Models\Category;

class NewsController extends BaseController{

    private static $langName = 'news';

    public function __construct(){
        parent::__construct();

        view()->share('lang_mod', $this->mod . '/' . self::$langName);

        $actions = request()->route()->getAction();
        $this->prefixUrl = $actions['prefix'];
    }


    public function getNewsList(Request $request){

        //dd(public_path(), resource_path(), app_path(), __DIR__, base_path('../abc.jpg'));

        $fields = [
            'news.id',
            'news.cat_id',
            //'news.cat_parent_id',
            'news.title',
            'news.alias',
            'news.featured_image',
            'news.created_at',
            'news.modified_at',
            'news.approved_at',
            'news.published_at',
            'news.created_by',
            'news.modified_by',
            'news.approved_by',
            'news.status',
            //'news.ordering',
            //'news.keywords',
            'news.is_trashed',
            'news.is_highlight',
            'news.is_hot',
            'news.is_from_source',
            'news.is_auto_published',
            'news.news_type',
            'category.cat_name',
            //'admin.first_name',
            //'admin.surname'
        ];



        $cat_id = $request->input('cat_id', '');
        $checked_type = $request->input('news_type', '0');
        $misc = $request->input('miscellaneous', 'all');
        $q = $request->input('q', '');
        $display_rows = $request->input('rows_per_page', config('backend.rows_per_page.20'));

        $where = null;
        if($misc != 'all'){
            $where = [
                'news.'.$misc => ['=', 1]
            ];
        }


        $parent_cats = Category::getParentList();

        $tree_data = [];
        if(count($parent_cats) > 0){
            $cat_fields = ['id', 'parent_id', 'cat_name', 'alias', 'published', 'ordering'];
            $tree_data = Category::createTreeList($parent_cats, $cat_fields, true);
        }

        //dd($tree_data);



        //dd($request->all(), $where);

        $news_list = News::getNewsList($fields, 0, $where, $checked_type, $cat_id, $q, $display_rows);

        //dd($news_list->toArray());

        $news_status = [];
        $cfg_status = config('backend.news_status');
        foreach($cfg_status as $k => $v){
            $news_status[config('constant.' . $k)] = trans($this->mod . '/' . self::$langName . ".$v");
        }

        $news_status_color = [];
        $cfg_status_color = config('backend.news_status_label_color');
        foreach($cfg_status_color as $k => $v){
            $news_status_color[config('constant.' . $k)] = $v;
        }


        $news_type = [];
        $cfg_type = config('backend.news_type');
        foreach($cfg_type as $k => &$v){
            $v[1] = trans($this->mod . '/' . self::$langName . ".$v[1]");
            $news_type[config('constant.' . $k)] = $v;
        }


        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());


        return view(
            'Backend::news.list-news',
            [
                'prefix_url' => $this->prefixUrl,
                'tree_data' => $tree_data,
                'news_list' => $news_list,
                'news_status' => $news_status,
                'news_type' => $news_type,
                'news_status_color' => $news_status_color,
                'rows_per_page' => config('backend.rows_per_page'),
                'form_qs' => $form_qs,
                'qs' => $qs,
                'cat_id' => $cat_id,
                'checked_type' => $checked_type,
                'misc' => $misc,
                'display_rows' => $display_rows,
                'q' =>$q,
            ]
        );

    }

    public function getTrashedList(Request $request){

        $fields = [
            'news.id',
            'news.cat_id',
            //'news.cat_parent_id',
            'news.title',
            'news.alias',
            'news.featured_image',
            'news.created_at',
            'news.modified_at',
            'news.approved_at',
            'news.published_at',
            'news.created_by',
            'news.modified_by',
            'news.approved_by',
            'news.status',
            //'news.ordering',
            //'news.keywords',
            'news.is_trashed',
            'news.is_highlight',
            'news.is_hot',
            'news.is_from_source',
            'news.is_auto_published',
            'news.news_type',
            'category.cat_name',
            //'admin.first_name',
            //'admin.surname'
        ];

        $cat_id = $request->input('cat_id', '');
        $checked_type = $request->input('news_type', '0');
        $misc = $request->input('miscellaneous', 'all');
        $q = $request->input('q', '');
        $display_rows = $request->input('rows_per_page', config('backend.rows_per_page.20'));

        $where = null;
        if($misc != 'all'){
            $where = [
                'news.'.$misc => ['=', 1]
            ];
        }

        $parent_cats = Category::getParentList();

        $tree_data = [];
        if(count($parent_cats) > 0){
            $cat_fields = ['id', 'parent_id', 'cat_name', 'alias', 'published', 'ordering'];
            $tree_data = Category::createTreeList($parent_cats, $cat_fields, true);
        }

        //dd($tree_data);

        $news_list = News::getNewsList($fields, 1, $where, $checked_type, $cat_id, $q, $display_rows);

        //dd($news_list->toArray());

        $news_status = [];
        $cfg_status = config('backend.news_status');
        foreach($cfg_status as $k => $v){
            $news_status[config('constant.' . $k)] = trans($this->mod . '/' . self::$langName . ".$v");
        }

        $news_status_color = [];
        $cfg_status_color = config('backend.news_status_label_color');
        foreach($cfg_status_color as $k => $v){
            $news_status_color[config('constant.' . $k)] = $v;
        }

        $news_type = [];
        $cfg_type = config('backend.news_type');
        foreach($cfg_type as $k => &$v){
            $v[1] = trans($this->mod . '/' . self::$langName . ".$v[1]");
            $news_type[config('constant.' . $k)] = $v;
        }

        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());


        return view(
            'Backend::news.list-trashed-news',
            [
                'prefix_url' => $this->prefixUrl,
                'tree_data' => $tree_data,
                'news_list' => $news_list,
                'news_status' => $news_status,
                'news_type' => $news_type,
                'news_status_color' => $news_status_color,
                'rows_per_page' => config('backend.rows_per_page'),
                'form_qs' => $form_qs,
                'qs' => $qs,
                'cat_id' => $cat_id,
                'checked_type' => $checked_type,
                'misc' => $misc,
                'display_rows' => $display_rows,
                'q' =>$q,
            ]
        );
    }

    public function getHomeHighlightList(Request $request){
        $fields = [
            'news.id',
            'news.cat_id',
            //'news.cat_parent_id',
            'news.title',
            'news.alias',
            'news.featured_image',
            'news.created_at',
            'news.modified_at',
            'news.approved_at',
            'news.published_at',
            'news.created_by',
            'news.modified_by',
            'news.approved_by',
            'news.status',
            //'news.ordering',
            //'news.keywords',
            'news.is_trashed',
            'news.is_highlight',
            'news.is_hot',
            'news.is_from_source',
            'news.is_auto_published',
            'news.news_type',
            'category.cat_name',
            //'admin.first_name',
            //'admin.surname'
        ];

        $cat_id = $request->input('cat_id', '');
        $checked_type = $request->input('news_type', '0');
        $q = $request->input('q', '');
        $display_rows = $request->input('rows_per_page', config('backend.rows_per_page.20'));



        $parent_cats = Category::getParentList();

        $tree_data = [];
        if(count($parent_cats) > 0){
            $cat_fields = ['id', 'parent_id', 'cat_name', 'alias', 'published', 'ordering'];
            $tree_data = Category::createTreeList($parent_cats, $cat_fields, true);
        }

        $where = [
            'news.is_highlight' => ['=', 1]
        ];

        $news_list = News::getNewsList($fields, 0, $where, $checked_type, $cat_id, $q, $display_rows);

        //dd($news_list->toArray());

        $news_status = [];
        $cfg_status = config('backend.news_status');
        foreach($cfg_status as $k => $v){
            $news_status[config('constant.' . $k)] = trans($this->mod . '/' . self::$langName . ".$v");
        }

        $news_status_color = [];
        $cfg_status_color = config('backend.news_status_label_color');
        foreach($cfg_status_color as $k => $v){
            $news_status_color[config('constant.' . $k)] = $v;
        }

        $news_type = [];
        $cfg_type = config('backend.news_type');
        foreach($cfg_type as $k => &$v){
            $v[1] = trans($this->mod . '/' . self::$langName . ".$v[1]");
            $news_type[config('constant.' . $k)] = $v;
        }

        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());


        return view(
            'Backend::news.list-home-highlight-news',
            [
                'prefix_url' => $this->prefixUrl,
                'tree_data' => $tree_data,
                'news_list' => $news_list,
                'news_status' => $news_status,
                'news_type' => $news_type,
                'news_status_color' => $news_status_color,
                'rows_per_page' => config('backend.rows_per_page'),
                'form_qs' => $form_qs,
                'qs' => $qs,
                'cat_id' => $cat_id,
                'checked_type' => $checked_type,
                'display_rows' => $display_rows,
                'q' =>$q,
            ]
        );
    }

    public function getCatHighlightList(Request $request){
        $fields = [
            'news.id',
            'news.cat_id',
            //'news.cat_parent_id',
            'news.title',
            'news.alias',
            'news.featured_image',
            'news.created_at',
            'news.modified_at',
            'news.approved_at',
            'news.published_at',
            'news.created_by',
            'news.modified_by',
            'news.approved_by',
            'news.status',
            //'news.ordering',
            //'news.keywords',
            'news.is_trashed',
            'news.is_highlight',
            'news.is_hot',
            'news.is_from_source',
            'news.is_auto_published',
            'news.news_type',
            'category.cat_name',
            //'admin.first_name',
            //'admin.surname'
        ];

        $cat_id = $request->input('cat_id', '');
        $checked_type = $request->input('news_type', '0');
        $q = $request->input('q', '');
        $display_rows = $request->input('rows_per_page', config('backend.rows_per_page.20'));

        $parent_cats = Category::getParentList();

        $tree_data = [];
        if(count($parent_cats) > 0){
            $cat_fields = ['id', 'parent_id', 'cat_name', 'alias', 'published', 'ordering'];
            $tree_data = Category::createTreeList($parent_cats, $cat_fields, true);
        }

        $where = [
            'news.is_cat_highlight' => [
                '=', 1
            ]
        ];

        $news_list = News::getNewsList($fields, 0, $where, $checked_type, $cat_id, $q, $display_rows);

        //dd($news_list->toArray());

        $news_status = [];
        $cfg_status = config('backend.news_status');
        foreach($cfg_status as $k => $v){
            $news_status[config('constant.' . $k)] = trans($this->mod . '/' . self::$langName . ".$v");
        }

        $news_status_color = [];
        $cfg_status_color = config('backend.news_status_label_color');
        foreach($cfg_status_color as $k => $v){
            $news_status_color[config('constant.' . $k)] = $v;
        }

        $news_type = [];
        $cfg_type = config('backend.news_type');
        foreach($cfg_type as $k => &$v){
            $v[1] = trans($this->mod . '/' . self::$langName . ".$v[1]");
            $news_type[config('constant.' . $k)] = $v;
        }

        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());


        return view(
            'Backend::news.list-cat-highlight-news',
            [
                'prefix_url' => $this->prefixUrl,
                'tree_data' => $tree_data,
                'news_list' => $news_list,
                'news_status' => $news_status,
                'news_type' => $news_type,
                'news_status_color' => $news_status_color,
                'rows_per_page' => config('backend.rows_per_page'),
                'form_qs' => $form_qs,
                'qs' => $qs,
                'cat_id' => $cat_id,
                'checked_type' => $checked_type,
                'display_rows' => $display_rows,
                'q' =>$q,
            ]
        );
    }

    public function getNewsForm(Request $request, $id=null){

        $action = $request->segment(2);

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $parent_cats = Category::getParentList();

        $tree_data = [];
        if(count($parent_cats) > 0){
            $cat_fields = ['id', 'parent_id', 'cat_name', 'alias', 'published', 'ordering'];
            $tree_data = Category::createTreeList($parent_cats, $cat_fields, true);
        }

        $news_status = [];
        $cfg_status = config('backend.news_status');
        foreach($cfg_status as $k => $v){
            $news_status[config('constant.' . $k)] = trans($this->mod . '/' . self::$langName . ".$v");
        }


        $news_type = [];
        $cfg_type = config('backend.news_type');
        foreach($cfg_type as $k => &$v){
            $v[1] = trans($this->mod . '/' . self::$langName . ".$v[1]");
            $news_type[config('constant.' . $k)] = $v;
        }

        $checked_type = 1;



        if($id != null && $action == 'edit'){

            $news = News::with('detail')
                ->with('meta_data')
                ->with('home_highlight')
                ->with('cat_highlight')
                ->with('news_relation')
                ->findOrFail($id);

            //dd($news->toArray());

            if($news->status != config('constant.NEWS_DRAFT_STATUS')){
                unset($news_status[config('constant.NEWS_DRAFT_STATUS')]);
            }
            else{
                unset($news_status[config('constant.NEWS_UNPUBLISHED_STATUS')]);
            }

            if($news->status == config('constant.NEWS_PUBLISHED_STATUS')){
                unset($news_status[config('constant.NEWS_AUTO_PUBLISHED_STATUS')]);
                unset($news_status[config('constant.NEWS_PENDING_STATUS')]);
            }

            //dd($news->toArray());

            $news->featured_image = $news->parseFeaturedImageUrl();

            $news_relation_ids = '';

            $relations = null;
            if($news->news_relation != null && $news->news_relation->relation_ids != ''){
                $news_relation_ids = $news->news_relation->relation_ids;

                if(old('relation_ids'))
                    $news_relation_ids = old('relation_ids');

                $relations = News::where('is_trashed', 0)
                    ->where('status', config('constant.NEWS_PUBLISHED_STATUS'))
                    ->whereIn('id', explode(',', $news_relation_ids))
                    ->select(['id', 'title', 'intro_content', 'alias'])
                    ->get();

            }


            return view(
                'Backend::news.edit-news',
                [
                    'prefix_url' => $this->prefixUrl,
                    'tree_data' => $tree_data,
                    'news_status' => $news_status,
                    'news_type' => $news_type,
                    //'checked_type' => $checked_type,
                    'news_relation_ids' => $news_relation_ids,
                    'relations' => $relations,
                    'news' => $news,
                    'qs' => $qs
                ]
            );
        }

        unset($news_status[config('constant.NEWS_UNPUBLISHED_STATUS')]);

        $draft = null;
        $relations = null;
        $news_relation_ids = '';

        if(!$request->session()->has('is_drafted')){

            $draft = News::create(['created_at' => date('Y-m-d H:i'), 'created_by' => $this->guard->user()->id]);
            $this->_saveDetail($draft, ['main_content' => '']);
            $this->_saveMetaData($draft, []);
            $this->_saveHitCounter($draft);
            session()->put('is_drafted', $draft->id);

        }
        else{

            $id = session()->get('is_drafted');

            $draft = News::with('detail')
                ->with('meta_data')
                ->with('home_highlight')
                ->with('cat_highlight')
                ->findOrFail($id);


            if(old('relation_ids'))
                $news_relation_ids = old('relation_ids');

            $relations = News::where('is_trashed', 0)
                ->where('status', config('constant.NEWS_PUBLISHED_STATUS'))
                ->whereIn('id', explode(',', $news_relation_ids))
                ->select(['id', 'title', 'intro_content', 'alias'])
                ->get();


        }


        return view(
            'Backend::news.create-news',
            [
                'draft' => $draft,
                'news_relation_ids' => $news_relation_ids,
                'relations' => $relations,
                'prefix_url' => $this->prefixUrl,
                'tree_data' => $tree_data,
                'news_status' => $news_status,
                'news_type' => $news_type,
                'checked_type' => $checked_type,
                'qs' => $qs
            ]
        );


    }

    public function getCancelNews(Request $request, $action='create'){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        if($action == 'create'){
            if($request->session()->has('is_drafted')){
                $request->session()->forget('is_drafted');
            }
        }

        return redirect($this->prefixUrl . $qs);

    }
    
    public function postCreateNews(NewsPostRequest $request, $id=null){

        $save_draft = $request->input('save_draft');

        $id = $request->input('id');

        $relation_data = $request->only(['relation_ids']);

        $news_data = $request->only(['cat_id', 'title', 'alias', 'intro_content', 'status', 'published_at', 'news_type', 'is_hot', 'is_highlight', 'is_cat_highlight', 'tags', 'featured_image']);



        $news_detail = $request->only(['main_content']);

        $highlight_data = $request->only(['highlight_time', 'cat_highlight_time']);

        $meta_data = $request->only(['meta_title', 'meta_keywords', 'meta_description', 'og_title', 'og_description', 'og_image']);


        if($save_draft == 1){
            $news_data['status'] = config('constant.NEWS_DRAFT_STATUS');
        }

        if($news_data['alias'] == ''){
            $news_data['alias'] = Vii::makeAlias($news_data['title']);
        }
        else{
            $news_data['alias'] = Vii::makeAlias($news_data['alias']);
        }

        $news_data['is_auto_published'] = 0;
        if(intval($news_data['status']) == config('constant.NEWS_AUTO_PUBLISHED_STATUS')){

            //Publish now
            if($news_data['published_at'] == ''){
                $news_data['published_at'] = date('Y-m-d H:i');
                $news_data['approved_at'] = $news_data['published_at'];
                $news_data['status'] = config('constant.NEWS_PUBLISHED_STATUS');
            }
            else{
                $news_data['published_at'] = Vii::formatDateTime($news_data['published_at'], 'Y-m-d H:i');
                $news_data['is_auto_published'] = 1;
            }

            $news_data['approved_at'] = date('Y-m-d H:i');
        }
        else if(intval($news_data['status']) == config('constant.NEWS_PUBLISHED_STATUS')){
            $news_data['published_at'] = date('Y-m-d H:i');
            $news_data['approved_at'] = $news_data['published_at'];
        }
        else{
            $news_data['published_at'] = null;
            $news_data['approved_at'] = null;
        }


//        if(!isset($news_data['is_highlight']))
//            $news_data['is_highlight'] = 0;
//
//        if(!isset($news_data['is_cat_highlight']))
//            $news_data['is_cat_highlight'] = 0;
//
//        if(!isset($news_data['is_hot']))
//            $news_data['is_hot'] = 0;

        $news_data['is_highlight'] = intval($news_data['is_highlight']);
        $news_data['is_cat_highlight'] = intval($news_data['is_cat_highlight']);
        $news_data['is_hot'] = intval($news_data['is_hot']);

        if(!isset($news_data['is_cat_highlight']))
            $news_data['is_cat_highlight'] = 0;

        if(!isset($news_data['is_hot']))
            $news_data['is_hot'] = 0;

        $news_data['is_from_source'] = 0;
        $news_data['url_source'] = '';

        $news_data['created_by'] = $this->guard->user()->id;
        $news_data['created_at'] = date('Y-m-d H:i');
        $news_data['is_trashed'] = 0;



        //dd($news_data);

        $qs = Vii::queryStringBuilder($request->getQueryString());


        $news_item = News::find($id);
        $rs = false;
        if($news_item == null){
            $rs = $news_item = new News($news_data);
        }
        else{
            $rs = $news_item->update($news_data);
        }

        if($rs){

            $rs &= $this->_saveDetail($news_item, $news_detail);

            //Meta data
            $rs &= $this->_saveMetaData($news_item, $meta_data);

            //Auto publish
            if($news_data['is_auto_published'] == 1){
                $rs &= $this->_saveAutoPublish($news_item);

            }

            //is highlight
            if($news_data['is_highlight'] == 1){
                $rs &= $this->_saveHomeHighlight($news_item, $highlight_data);

            }

            //is cat hightlight
            if($news_data['is_cat_highlight'] == 1){
                $rs &= $this->_saveCatHighlight($news_item, $highlight_data);
            }

            if($relation_data['relation_ids'] != ''){
                $rs &= $this->_saveNewsRelation($news_item, $relation_data);
            }

            $rs &= $this->_saveHitCounter($news_item);


            if(!$rs){
                return redirect()->back()->with('message-error', trans($this->mod . '/' . self::$langName . '.news_create_error'));
            }

//            if($save_draft == 0){
//                return redirect($this->prefixUrl . $qs);
//            }


            if($request->session()->has('is_drafted')){
                $request->session()->forget('is_drafted');
            }

            return redirect(url($this->prefixUrl . '/edit', [$news_item->id]) . $qs)->with('message-success', trans($this->mod . '/' . self::$langName . '.news_success'));

        }


        return redirect()->back()->with('message-error', trans($this->mod . '/' . self::$langName . '.news_create_error'));

        //dd($save_draft, $news_data, $meta_data, $highlight_data, $highlight);

    }

    public function putEditNews(NewsPostRequest $request, $id=null){

        //dd($request->all());

        $id = $request->input('id');

        $relation_data = $request->only(['relation_ids']);

        $news_item = News::findOrFail($id);

        $news_data = $request->only(['cat_id', 'title', 'alias', 'intro_content', 'main_content', 'status', 'published_at', 'news_type', 'is_hot', 'is_highlight', 'is_cat_highlight', 'tags']);

        //dd($news_data);

        $news_detail = $request->only(['main_content']);

        $highlight_data = $request->only(['highlight_time', 'cat_highlight_time']);

        $meta_data = $request->only(['meta_title', 'meta_keywords', 'meta_description', 'og_title', 'og_description', 'og_image']);



        if($news_data['alias'] == ''){
            $news_data['alias'] = Vii::makeAlias($news_data['title']);
        }
        else{
            $news_data['alias'] = Vii::makeAlias($news_data['alias']);
        }



        $news_data['is_auto_published'] = 0;
        if(intval($news_data['status']) == config('constant.NEWS_AUTO_PUBLISHED_STATUS')){

            //Publish now
            if($news_data['published_at'] == ''){
                $news_data['published_at'] = date('Y-m-d H:i');
                $news_data['approved_at'] = $news_data['published_at'];
                $news_data['status'] = config('constant.NEWS_PUBLISHED_STATUS');
            }
            else{
                $news_data['published_at'] = Vii::formatDateTime($news_data['published_at'], 'Y-m-d H:i');
                $news_data['is_auto_published'] = 1;
            }

            $news_data['approved_at'] = date('Y-m-d H:i');
            $news_data['published_by'] = $this->guard->user()->id;
            $news_data['approved_by'] = $this->guard->user()->id;
        }
        else if(intval($news_data['status']) == config('constant.NEWS_PUBLISHED_STATUS')){
            $news_data['published_at'] = date('Y-m-d H:i');
            $news_data['approved_at'] = $news_data['published_at'];
            $news_data['published_by'] = $this->guard->user()->id;
            $news_data['approved_by'] = $this->guard->user()->id;
        }
        else{
            $news_data['published_at'] = null;
            $news_data['approved_at'] = null;
        }


        $news_data['is_highlight'] = intval($news_data['is_highlight']);
        $news_data['is_cat_highlight'] = intval($news_data['is_cat_highlight']);
        $news_data['is_hot'] = intval($news_data['is_hot']);

        $news_data['is_from_source'] = 0;
        $news_data['url_source'] = '';

        $news_data['modified_by'] = $this->guard->user()->id;
        $news_data['modified_at'] = date('Y-m-d H:i');
        $news_data['is_trashed'] = 0;

        if($request->input('featured_image') != ''){
            $news_data['featured_image'] = $request->input('featured_image');
        }

        //dd($news_data, $relation_data);

        $qs = Vii::queryStringBuilder($request->getQueryString());


        if($news_item->update($news_data)){

            $rs = true;

            $rs &= $this->_saveDetail($news_item, $news_detail);

            //Meta data
            $rs &= $this->_saveMetaData($news_item, $meta_data);

            //Auto publish
            $rs &= $this->_saveAutoPublish($news_item);

            //is highlight
            $rs &= $this->_saveHomeHighlight($news_item, $highlight_data);

            //is cat hightlight
            $rs &= $this->_saveCatHighlight($news_item, $highlight_data);

            //news relation
            $rs &= $this->_saveNewsRelation($news_item, $relation_data);


            if(!$rs){
                return redirect()->back()->with('message-error', trans($this->mod . '/' . self::$langName . '.news_edit_error'));
            }

            //return redirect($this->prefixUrl . $qs);
            return redirect(url($this->prefixUrl . '/edit', [$news_item->id]) . $qs)->with('message-success', trans($this->mod . '/' . self::$langName . '.news_success'));

        }


        return redirect()->back()->with('message-error', trans($this->mod . '/' . self::$langName . '.news_edit_error'));

        //dd($save_draft, $news_data, $meta_data, $highlight_data, $highlight);

    }

    public function getTrashNews(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        News::where('id', $id)->update(['is_trashed' => 1]);

        return redirect($this->prefixUrl . $qs);
    }

    public function getRestoreNews(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $news = News::find($id);

        if($news != null){
            if($news != null){
                switch($news->status){
                    case config('constant.NEWS_PUBLISHED_STATUS'):
                        $news->update(['is_trashed' => 0, 'status' => config('constant.NEWS_UNPUBLISHED_STATUS')]);
                        break;

                    case config('constant.NEWS_UNPUBLISHED_STATUS'):
                        $news->update(['is_trashed' => 0, 'status' => config('constant.NEWS_PUBLISHED_STATUS')]);
                        break;

                    default:
                        $news->update(['is_trashed' => 0]);
                        break;

                }

            }
        }

        //News::where('id', $id)->update(['is_trashed' => 0]);

        return redirect($this->prefixUrl . '/trashed' . $qs);
    }

    public function getDeleteNews(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        News::destroy($id);//News::where('votes', '>', 100)->delete();

        NewsDetail::where('news_id', $id)->delete();
        NewsMeta::where('news_id', $id)->delete();
        NewsHomeHighlight::where('news_id', $id)->delete();
        NewsCatHighlight::where('news_id', $id)->delete();
        NewsAutoPublish::where('news_id', $id)->delete();
        NewsHitCounter::where('news_id', $id)->delete();

        return redirect($this->prefixUrl . '/trashed' . $qs);
    }

    public function getRemoveHomeHighlight(Request $request, $id=null){
        $qs = Vii::queryStringBuilder($request->getQueryString());

        $news = News::where('id', $id)
            ->where('is_highlight', 1)
            ->with('home_highlight')
            ->select(['id', 'is_highlight', 'status'])->first();

        if($news != null){
            if($news->home_highlight != null){
                $news->home_highlight->delete();
            }

            $news->update(['is_highlight' => 0]);
        }

        return redirect($this->prefixUrl . '/home-highlight' . $qs);
    }

    public function getRemoveCatHighlight(Request $request, $id=null){
        $qs = Vii::queryStringBuilder($request->getQueryString());

        $news = News::where('id', $id)
            ->where('is_cat_highlight', 1)
            ->with('cat_highlight')
            ->select(['id', 'is_cat_highlight', 'status'])->first();

        if($news != null){
            if($news->cat_highlight != null){
                $news->cat_highlight->delete();
            }

            $news->update(['is_cat_highlight' => 0]);
        }

        return redirect($this->prefixUrl . '/cat-highlight' . $qs);
    }

    public function getPublishedNews(Request $request, $id=null){
        $qs = Vii::queryStringBuilder($request->getQueryString());

        $news = News::findOrFail($id);

        if($news != null){
            switch($news->status){
                case config('constant.NEWS_PUBLISHED_STATUS'):
                    $news->update(['status' => config('constant.NEWS_UNPUBLISHED_STATUS')]);
                    break;

                case config('constant.NEWS_UNPUBLISHED_STATUS'):
                    $news->update(['status' => config('constant.NEWS_PUBLISHED_STATUS')]);
                    break;

                default:
                    break;

            }

        }


        return redirect($this->prefixUrl . $qs);
    }

    public function getAjaxMediaList(Request $request, $id=null){
        if($request->ajax()){

            $files = FileUploader::where('news_id', $id)
                ->select(['id', 'ori_name', 'file_name', 'cdn_name', 'root_url'])
                ->get();

            $data = [];
            if(count($files) > 0){
                foreach($files as $v){

                    $uri = config('app.url') . '/' . $v->root_url;
                    if($v->cdn_name != '')
                        $uri = $v->cdn_name . '/' . $v->root_url;

                    $tmp = [
                        'id' => $v->id,
                        'ori_name' => $v->ori_name,
                        'file_name' => $v->id . '@' . $v->file_name,
                        'uri' => $uri . '/' . $v->file_name
                    ];

                    $data[] = $tmp;
                }
            }

            return response()->json($data);

        }
    }

    public function postAjaxUploadFile(Request $request, $id=null){

        if($request->ajax()){
            $id = $request->input('news_id');
            if($request->hasFile('file_uploader')){
                $ori_name = $request->file('file_uploader')->getClientOriginalName();
                $ext = $request->file('file_uploader')->getClientOriginalExtension();
                $uid = uniqid('vii');
                $file_name = md5($ori_name . time() . $uid) . '-' . $uid . '.' . $ext;
                //$file_name = sha1_file($request->file('file_uploader')->getPathname()) . '.' . $ext;

                $file_data = [
                    'news_id' => $id,
                    'ori_name' => $ori_name,
                    'file_name' => $file_name,
                    'file_ext' => $ext,
                    'file_unique_key' => sha1($ori_name . time() . uniqid(config('backend.guid_key'))),

                ];

                if(config('backend.file_uploader.USE_CDN')){
                    $cdn = config('backend.file_uploader.CDN.CDN_1');
                    $file_data['cdn_name'] = $cdn['host'];
                    $file_data['file_path'] = $cdn['file_path'];
                    $file_data['root_url'] = $cdn['root_url'];

                    $ftp = new FTPConnection(config('backend.file_uploader.FTP.FTP_1'));

                    $src_file = $request->file('file_uploader')->getPathname();
                    $dest_file = $cdn['file_path'] . '/' . $file_name;

                    if($ftp->uploadFile($src_file, $dest_file, FTP_BINARY)){
                        $f = new FileUploader($file_data);
                        $f->save();
                    }
                }
                else{
                    $file_data['cdn_name'] = '';
                    $file_data['file_path'] = '';
                    $file_data['root_url'] = config('backend.file_uploader.LOCAL_PATH_IMAGE');
                    
                    $dest_path = config('backend.file_uploader.ROOT_PATH') . '/' . config('backend.file_uploader.LOCAL_PATH_IMAGE');
                    if($request->file('file_uploader')->move($dest_path, $file_name)){
                        $f = new FileUploader($file_data);
                        if($f->save()){

                            //Vii::pr($file_data,"D:\\file.txt", true);

                            return response()->json([
                                'id' => $f->id,
                                'ori_name' => $f->ori_name,
                                'file_name' => $f->file_name,
                                'uri' => 'http://localhost:8080/zoyocms/storage/images/' . $f->file_name

                            ]);
                        }
                        return response()->json(['error'=>"Can not filled image info to database."], 400);
                    }
                    return response()->json(['error'=>"Can not upload."], 400);

                }

            }

            return response()->json(['error'=>"No file upload"], 400);

        }
    }

    public function postAjaxRelatedNews(Request $request){

        if($request->ajax()){

            $tags = $request->input('tags');

            if(!empty($tags)) {

                $atag = [];
                if ($tags != '')
                    $atag = explode(',', $tags);


                $sql = News::leftJoin('news_detail', 'news.id', '=', 'news_detail.news_id')
                        ->where('news.is_trashed', 0)
                        ->where('news.status', config('constant.NEWS_PUBLISHED_STATUS'));

                $search_fields = ['news.title', 'news.intro_content', 'news_detail.main_content'];
                $sql = Vii::makeSearchExactWords($sql, $search_fields, $atag);

                $items = $sql->select(['news.id', 'news.title', 'news.alias', 'news.intro_content'])->get();

                //dd($items->toArray());
                //Vii::pr($items->toArray(), "D:\\data.txt");

                return response()->json($items->toArray());
            }

            return response()->json([]);

        }
    }


    /*-----------------------------------------------------------------------------*/

    private function _saveDetail($news, $detail_data){

        $detail = NewsDetail::where('news_id', $news->id)->first();

        if($detail == null){
            $detail_data['news_id'] = $news->id;
            $detail = new NewsDetail($detail_data);
            return $detail->save();
        }

        return $detail->update($detail_data);


    }

    private function _saveHitCounter($news){
        $counter = NewsHitCounter::firstOrNew(['news_id'=>$news->id]);
        $counter->hit_counter = 0;
        return $counter->save();
    }

    private function _saveNewsRelation($news, $relation_data){

        $relation = NewsRelation::where('news_id', $news->id)->first();

        if ($relation == null) {
            $relation_data['news_id'] = $news->id;
            $relation = new NewsRelation($relation_data);
            return $relation->save();

        }
        else{
            if($relation_data['relation_ids'] == ''){
                return $relation->delete();
            }

            return $relation->update($relation_data);
        }

    }

    private function _saveMetaData($news, $meta_data){

        $meta = NewsMeta::where('news_id', $news->id)->first();

        if($meta == null){
            $meta_data['news_id'] = $news->id;
            $meta = new NewsMeta($meta_data);
            return $meta->save();
        }

        return $meta->update($meta_data);

    }

    private function _saveHomeHighlight($news, $highlight_data){

        $highlight = [];
        $highlight['hl_start_time'] = null;
        $highlight['hl_end_time'] = null;

        if($highlight_data['highlight_time'] != ''){
            $hl_time = explode('-', $highlight_data['highlight_time']);
            $highlight['hl_start_time'] = Vii::formatDateTime(trim($hl_time[0]), 'Y-m-d H:i');
            $highlight['hl_end_time'] = Vii::formatDateTime(trim($hl_time[1]), 'Y-m-d H:i');
        }

        $home_highlight = NewsHomeHighlight::where('news_id', $news->id)->first();

        if($news->is_highlight == 1){
            if($home_highlight == null){
                $highlight['news_id'] = $news->id;
                $home_highlight = new NewsHomeHighlight($highlight);
                return $home_highlight->save();
            }
            else{
                return $home_highlight->update($highlight);
            }
        }


        if($home_highlight != null){
            return $home_highlight->delete();
        }

        return true;

    }

    private function _saveCatHighlight($news, $highlight_data){

        $highlight = [];

        $highlight['hl_start_time'] = null;
        $highlight['hl_end_time'] = null;

        if($highlight_data['cat_highlight_time'] != ''){
            $hl_time = explode('-', $highlight_data['cat_highlight_time']);
            $highlight['hl_start_time'] = Vii::formatDateTime(trim($hl_time[0]), 'Y-m-d H:i');
            $highlight['hl_end_time'] = Vii::formatDateTime(trim($hl_time[1]), 'Y-m-d H:i');
        }

        $cat_highlight = NewsCatHighlight::where('news_id', $news->id)->first();

        if($news->is_cat_highlight == 1){
            if($cat_highlight == null){
                $highlight['news_id'] = $news->id;
                $highlight['cat_id'] = $news->cat_id;
                $cat_highlight = new NewsCatHighlight($highlight);
                return $cat_highlight->save();
            }
            else{
                $highlight['cat_id'] = $news->cat_id;
                return $cat_highlight->update($highlight);
            }
        }

        if($cat_highlight != null){
            return $cat_highlight->delete();
        }

        return true;
    }

    private function _saveAutoPublish($news){

        $auto_publish_data = [];

        $auto = NewsAutoPublish::where('news_id', $news->id)->first();

        if($news->is_auto_published == 1){
            if($auto == null){
                $auto_publish_data['news_id'] = $news->id;
                $auto_publish_data['published_at'] = $news->published_at;
                $auto_publish_data['is_done'] = 0;
                $auto = new NewsAutoPublish($auto_publish_data);
                return $auto->save();
            }
            else{
                if($auto->is_done == 0){
                    $auto_publish_data['published_at'] = $news->published_at;
                    return $auto->update($auto_publish_data);
                }
            }
        }

        //Delele if status changed is not AUTO_PUBLISHED
        if($auto != null){
            return $auto->delete();
        }

        return true;

    }


//    private function _getFeaturedImageUri($featured, $img){
//        if($featured == null)
//            return "";
//
//        if($featured->cdn == ''){
//            return config('app.url') . '/' . $featured->root_url . '/' . $img;
//        }
//
//        return $featured->cdn . '/' . $featured->root_url . '/'. $img;
//
//    }
}