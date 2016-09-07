<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model{

    protected $table = 'category';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'parent_id',
        'cat_name',
        'alias',
        'ordering',
        'published',
        'is_trashed',
        'created_at',
        'modified_at'

    ];

    protected $guarded = [
        'id'
    ];

    public function news(){
        return $this->hasMany('App\Models\News', 'cat_id');
    }

    public function parent(){
        return $this->belongsTo('App\Models\Category', 'parent_id');
    }

    public function children(){
        return $this->hasMany('App\Models\Category', 'parent_id');
    }


    /*--------------BACKEND-------------*/
    public static function getParentList(){

        $fields = ['id', 'parent_id', 'cat_name', 'alias', 'published', 'ordering'];

        $parent_cats = Category::where('is_trashed', 0)
            ->where('parent_id', 0)
            ->where('published', '>=', 0)

            ->select($fields)
            ->orderBy('ordering', 'ASC')
            ->get();

        return $parent_cats;
    }
    
    public static function createTreeList($parents, $fields=[], $is_trait=false){
        
        if(empty($parents))
            return [];
        
        $list = [];
        foreach($parents as $k => &$parent){

            $parent->cat_name_tmp = $parent->cat_name;

            $list[] = $parent;

            $children = $parent->children()->where('published', '>=', 0)
                ->where('is_trashed', 0)
                ->select($fields)
                ->orderBy('ordering', 'ASC')
                ->get();

            $arr_children =  self::createChildrenList($children, $parent, $is_trait);

            $list = array_merge($list, $arr_children);


        }

        //dd($list);

        return $list;
        
    }
    
    private static function createChildrenList($children=[], $parent, $is_trait=false, $h=1){
        
        if(empty($children))
            return [];

        $list = [];
        foreach($children as $k => &$item){
            $item->cat_name_tmp = $item->cat_name;
            if(!$is_trait){
                $str_indent = "";
                for($i=1; $i<$h ;$i++)
                    $str_indent .= "&nbsp;&nbsp;&nbsp;&nbsp;";

                $str_indent .= "&#8627;&nbsp;&nbsp;&nbsp;&nbsp;";
                //$str_indent .= "&#10551;&nbsp;&nbsp;&nbsp;&nbsp;";

                $item->cat_name_tmp = $str_indent . $item->cat_name_tmp;

            }
            else{
                //$item->cat_name = $parent->cat_name . '&nbsp;&#10097;&nbsp;' . $item->cat_name;
                $item->cat_name_tmp = $parent->cat_name_tmp . '&nbsp;&#10148;&nbsp;' . $item->cat_name_tmp;
            }

            $list[] = $item;

            $temp_children = $item->children();
            if($temp_children != null){
                $arr_children = self::createChildrenList($temp_children, $item->id, $h + 1);
                $list = array_merge($list, $arr_children);
            }

        }

        return $list;
    }

    /*--------------BACKEND-------------*/

    /*--------------FRONTEND-------------*/

    public static function getFrontendParentList(){
        $fields = ['id', 'parent_id', 'cat_name', 'alias'];

        $list = Category::where('is_trashed', 0)
            ->where('parent_id', 0)
            ->where('published', '=', 1)

            ->select($fields)
            ->orderBy('ordering', 'ASC')
            ->get();

        return $list;
    }

    public static function getFrontendChildrenList(){
        $fields = ['id', 'parent_id', 'cat_name', 'alias'];

        $list = Category::where('is_trashed', 0)
            ->where('parent_id', '>', 0)
            ->where('published', '=', 1)

            ->select($fields)
            ->orderBy('ordering', 'ASC')
            ->get();

        return $list;
    }

    public static function getChildrenListFromParent($parent_id){
        $fields = ['id', 'parent_id', 'cat_name', 'alias'];

        $list = Category::where('is_trashed', 0)
            ->where('parent_id', $parent_id)
            ->where('published', '=', 1)

            ->select($fields)
            ->orderBy('ordering', 'ASC')
            ->get();

        return $list;
    }

    public static function getSiblingList($id, $parent_id){
        $fields = ['id', 'parent_id', 'cat_name', 'alias'];

        $list = Category::where('is_trashed', 0)
            ->where('parent_id', $parent_id)
            ->where('id', '!=', $id)
            ->where('published', '=', 1)

            ->select($fields)
            ->orderBy('ordering', 'ASC')
            ->get();

        return $list;
    }

    /*--------------FRONTEND-------------*/

}