<?php   namespace App\Modules\Backend\Controllers;

use App\Libs\Utils\Vii;
use Illuminate\Http\Request;

use App\Models\Category;

use App\Modules\Backend\Requests\CategoryPostRequest;

class CategoryController extends BaseController{

    private static $langName = 'category';

    public function __construct(){
        parent::__construct();

        view()->share('lang_mod', $this->mod . '/' . self::$langName);

        $actions = request()->route()->getAction();
        $this->prefixUrl = $actions['prefix'];
        //$this->prefixUrl = '/category';
    }


    public function getCategoryList(Request $request){
        $parent_cats = Category::getParentList();
        $tree_data = [];

        $fields = ['id', 'cat_name', 'alias', 'published', 'ordering'];

        if(count($parent_cats) > 0){
            //$tree_data = $this->createTreeList($parent_cats);
            $tree_data = Category::createTreeList($parent_cats, $fields);
        }

        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());

        return view(
            'Backend::category.list-cat',
            [
                'tree_data' => $tree_data,
                'prefix_url' => $this->prefixUrl,
                'form_qs' => $form_qs,
                'qs' => $qs
            ]
        );


    }

    public function getCategoryForm(Request $request, $id=null){

        $parent_cats = Category::getParentList();

        $fields = ['id', 'cat_name', 'alias', 'published', 'ordering'];

        $tree_data = [];
        if(count($parent_cats) > 0){
            $tree_data = Category::createTreeList($parent_cats, $fields, true);
        }


        $qs = Vii::queryStringBuilder($request->getQueryString());

        if($id != null){
            $cat = Category::findOrFail($id);

            return view(
                'Backend::category.edit-cat',
                [
                    'tree_data' => Vii::createOptionData($tree_data, 'id', 'cat_name_tmp', [''=>'---Root---']) ,
                    'prefix_url' => $this->prefixUrl,
                    'qs' => $qs,
                    'cat' => $cat
                ]
            );
        }

        return view(
            'Backend::category.create-cat',
            [
                'tree_data' => Vii::createOptionData($tree_data, 'id', 'cat_name_tmp', [''=>'---Root---']) ,
                'prefix_url' => $this->prefixUrl,
                'qs' => $qs
            ]
        );

    }

    public function postCreateCategory(CategoryPostRequest $request){

        $form = $request->only(['parent_id', 'cat_name', 'alias', 'published']);

        //dd($form);

        if($form['alias'] == ''){
            $form['alias'] = Vii::makeAlias($form['cat_name']);
        }
        else{
            $form['alias'] = Vii::makeAlias($form['alias']);
        }

        $cat = new Category($form);

        $qs = Vii::queryStringBuilder($request->getQueryString());

        if($cat->save()){
            return redirect($this->prefixUrl . $qs);
        }

        return redirect($this->prefixUrl . '/create' . $qs);

    }

    public function putEditCategory(CategoryPostRequest $request, $id=null){

        $id = $request->input('id');

        $form = $request->only(['parent_id', 'cat_name', 'alias', 'published']);

        if($form['alias'] == ''){
            $form['alias'] = Vii::makeAlias($form['cat_name']);
        }
        else{
            $form['alias'] = Vii::makeAlias($form['alias']);
        }

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $cat = Category::findOrFail($id);

        if($cat->update($form)){
            return redirect($this->prefixUrl . $qs);
        }

        return redirect($this->prefixUrl . '/create' . $qs);

    }

    public function postOrderingCategory(Request $request){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $ids = $request->input('ids');
        $ordering = $request->input('ordering');

        $arr_ordering = array_combine($ids, $ordering);

        Category::whereIn('id', $ids)
            ->chunk(100, function($models) use($arr_ordering) {
                foreach($models as $item){
                    $item->ordering = $arr_ordering[$item->id];
                    $item->save();
                }
            });


        return redirect($this->prefixUrl .  $qs);

    }

    public function getPublishedCategory(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $cat = Category::findOrFail($id);
        $val = 1 - $cat->published;
        $cat->update(['published'=> $val]);
        
        $this->publishedChildren($cat->children()->select(['id', 'published'])->get(), $val);

        return redirect($this->prefixUrl .  $qs);
    }

    private function publishedChildren($children, $published){
        if(empty($children))
            return false;

        $ids = [];
        foreach($children as $child){
            $ids[] = $child->id;
            $this->publishedChildren($child->children(), $published);
        }

        Category::whereIn('id', $ids)->update(['published' => $published]);

        return true;
    }

}