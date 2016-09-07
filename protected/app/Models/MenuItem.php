<?php   namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MenuItem extends Model{

    protected $table = 'menu_item';
    public $timestamps = false;
    //public $incrementing = false;

    protected $fillable = [
        'parent_id',
        'menu_id',
        'item_name',
        'cat_id',
        'custom_url',
        'status',
        'ordering',
        'is_trashed'

    ];

    protected $guarded = [
        'id'
    ];

    public function menu(){
        return $this->belongsTo('App\Models\Menu', 'menu_id');
    }

    public function parent(){
        return $this->belongsTo('App\Models\MenuItem', 'parent_id');
    }

    public function children(){
        return $this->hasMany('App\Models\MenuItem', 'parent_id');
    }
    

    public static function getParentList($fields='*', $menu_id){
        
        $parents = MenuItem::leftJoin('category', 'menu_item.cat_id', '=', 'category.id')
            ->where('menu_item.status', '>=', 0)
            ->where('menu_item.parent_id', 0)
            ->where('menu_item.menu_id', $menu_id)
            ->select($fields)
            ->orderBy('menu_item.ordering', 'ASC')
            ->get();

        return $parents;
    }
    
    public static function createTreeList(&$parents, $fields='*'){
        
        if(empty($parents))
            return false;
        
        
        foreach($parents as $k => &$parent){

            $children = $parent->children()->leftJoin('category', 'menu_item.cat_id', '=', 'category.id')
                ->where('menu_item.status', '>=', 0)
                ->select($fields)
                ->orderBy('menu_item.ordering', 'ASC')
                ->get();
            
            $parent->item_children = $children;

            self::createChildrenList($parent->item_children, $fields);

        }
  

        return true;
        
    }
    
    private static function createChildrenList(&$children, $fields){
        
        if(empty($children))
            return false;

        foreach($children as $k => &$item){

            $temp_children = $item->children()->leftJoin('category', 'menu_item.cat_id', '=', 'category.id')
                ->where('menu_item.status', '>=', 0)
                ->select($fields)
                ->orderBy('menu_item.ordering', 'ASC')
                ->get();
            
            if($temp_children != null){
                $item->item_children = $temp_children;
                self::createChildrenList($item->item_children, $fields);
            }

        }

        return true;
    }


}