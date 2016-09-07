<?php   namespace App\Modules\Backend\Controllers;



use App\Libs\Utils\Vii;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuCache;
use App\Models\MenuItem;
use App\Modules\Backend\Requests\MenuPostRequest;
use Illuminate\Http\Request;
use Form;

class MenuController extends BaseController{

    private static $langName = 'menu';

    public function __construct(){
        parent::__construct();

        view()->share('lang_mod', $this->mod . '/' . self::$langName);

        $actions = request()->route()->getAction();
        $this->prefixUrl = $actions['prefix'];
    }

    public function getMenuList(Request $request){
        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $menus = Menu::where('is_trashed', 0)
            ->select('*')
            ->get();

        return view(
            'Backend::menu.list-menu',
            [
                'menus' => $menus,
                'prefix_url' => $this->prefixUrl,
                'form_qs' => $form_qs,
                'qs' => $qs
            ]
        );

    }

    public function getTrashedList(Request $request){
        $append_qs = [];
        $append_qs['mid'] = $request->input('mid');
        $form_qs = Vii::queryStringBuilder($append_qs);

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $menus = Menu::where('is_trashed', 1)
            ->select('*')
            ->get();

        return view(
            'Backend::menu.list-trashed-menu',
            [
                'menus' => $menus,
                'prefix_url' => $this->prefixUrl,
                'form_qs' => $form_qs,
                'qs' => $qs
            ]
        );

    }
    
    public function getMenuForm(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        if($id != null){
            $menu = Menu::findOrFail($id);
            $parent_cats = Category::getParentList();
            $cat_fields = ['id', 'cat_name', 'published'];
            $tree_cat = Category::createTreeList($parent_cats, $cat_fields);

            //dd($tree_cat);

            $item_fields = [
                'menu_item.id',
                'menu_item.parent_id',
                'menu_item.item_name',
                'menu_item.cat_id',
                'menu_item.custom_url',
                'category.alias'
            ];

            $item_parents = MenuItem::getParentList($item_fields, $id);

            MenuItem::createTreeList($item_parents, $item_fields);

            //dd(json_encode($item_parents, JSON_UNESCAPED_UNICODE));


            return view(
                'Backend::menu.edit-menu',
                [
                    'menu' => $menu,
                    'menu_items' => $this->_renderMenuItemList($item_parents),
                    'tree_cat' => $tree_cat,
                    'prefix_url' => $this->prefixUrl,
                    'qs' => $qs
                ]
            );
        }

        return view(
            'Backend::menu.create-menu',
            [
                'prefix_url' => $this->prefixUrl,
                'qs' => $qs
            ]
        );
    }

    public function postCreateMenu(MenuPostRequest $request){

        $data = $request->only(['menu_title', 'menu_pos', 'status']);

        $data['is_trashed'] = 0;

        $menu = new Menu($data);

        $qs = Vii::queryStringBuilder($request->getQueryString());

        if($menu->save()){
            return redirect(url($this->prefixUrl . '/edit', [$menu->id]) . $qs);
        }

        return redirect()->back()->with('message-error', trans($this->mod . '/' . self::$langName . '.menu_create_error'));

    }

    public function putEditMenu(MenuPostRequest $request, $id=null){

        //dd($request->all());
        $id = $request->input('id');

        $item_ids = $request->input('item_id');
        $item_names = $request->input('item_name');
        $custom_urls = $request->input('custom_url');

        $menu_hierarchy_str= $request->input('menu_hierarchy');


        //Update Menu
        $menu = Menu::findOrFail($id);

        $menu->update($request->only(['menu_title', 'menu_pos', 'status']));

        //Delete Menu Item
        $deleted_ids = $request->input('deleted_menu_id');
        if($deleted_ids != ''){
            MenuItem::whereIn('id', explode(',', str_replace(' ', '', $deleted_ids)))->delete();
        }

        //Update Menu Item
        $item_data = [];
        for($i = 0; $i<count($item_ids); $i++){

            if($custom_urls[$i] != ''){
                $aparts = parse_url($custom_urls[$i]);
                if(!isset($aparts['scheme'])){
                    $custom_urls[$i] = 'http://' . $custom_urls[$i];
                }
            }


            $item_data[$item_ids[$i]] = [
                'parent_id' => 0,
                'item_name' => $item_names[$i],
                'custom_url' => $custom_urls[$i],
                'ordering' => 1
            ];
        }

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $menu_hierarchy = json_decode($menu_hierarchy_str, true);

        if(count($menu_hierarchy) > 0){
            //Update parent_id
            foreach($menu_hierarchy as $k => &$item){
                $item_data[$item['id']]['ordering'] = $k + 1;
                $this->_retrieveMenuHierarchy($item, $item_data);
            }

            //Update to database
            $_ids = array_keys($item_data);
            MenuItem::whereIn('id', $_ids)
                ->chunk(25, function($menu_items) use($item_data){
                    foreach($menu_items as $item){
                        $item->update($item_data[$item->id]);
                    }
                });

            //Update menu cached items
            $item_fields = [
                'menu_item.id',
                'menu_item.parent_id',
                'menu_item.item_name',
                'menu_item.cat_id',
                'menu_item.custom_url',
                'category.alias'
            ];

            $item_parents = MenuItem::getParentList($item_fields, $id);
            MenuItem::createTreeList($item_parents, $item_fields);

            $json_cache = json_encode($item_parents, JSON_UNESCAPED_UNICODE);

            $cache = MenuCache::find($id);
            if($cache == null){
                $cache = new MenuCache([
                    'menu_id' => $id,
                    'json_data' => $json_cache
                ]);

                $cache->save();
            }
            else{
                $cache->update(['json_data' => $json_cache]);
            }
        }

        return redirect(url($this->prefixUrl . '/edit', [$menu->id]) . $qs);

    }

    public function postAjaxCreateMenuItem(Request $request){

        if($request->ajax()){
            //Vii::pr($request->all(), 'D:\\data.txt');
            $menu_id = $request->input('menu_id');
            $cat_id = $request->input('cat_id', '');

            $data = [];

            $max_ordering = MenuItem::where('parent_id', 0)->max('ordering');

            if($cat_id != ''){
                $apart1 = explode(',', $cat_id);

                if(count($apart1) > 0){
                    foreach($apart1 as $p){
                        $apart2 = explode('|', $p);

                        $tmp = [
                            'menu_id' => $menu_id,
                            'item_name' => $apart2[1],
                            'cat_id' => $apart2[0],
                            'parent_id' => 0,
                            'status' => 1,
                            'custom_url' => '',
                            'ordering' => ++$max_ordering
                        ];

                        $item = MenuItem::create($tmp);

                        $data[] = $item->toArray();
                    }
                }
            }
            else{

                $custom_url = trim($request->input('custom_url', ''));
                $item_name = $request->input('item_name', '');

                if($custom_url != '' && $item_name != '') {
                    $aparts = parse_url($custom_url);

                    if(!isset($aparts['scheme'])){
                        $custom_url  = 'http://' . $custom_url;
                    }

                    $tmp = [
                        'menu_id' => $menu_id,
                        'item_name' => $item_name,
                        'custom_url' => $custom_url,
                        'cat_id' => 0,
                        'parent_id' => 0,
                        'status' => 1,
                        'ordering' => ++$max_ordering
                    ];

                    $item = MenuItem::create($tmp);

                    $data[] = $item->toArray();
                }

            }

            return response()->json($data);
        }
    }

    public function getPublishedMenu(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        $menu = Menu::findOrFail($id);
        $val = 1 - $menu->status;
        $menu->update(['status'=> $val]);

        return redirect($this->prefixUrl .  $qs);
    }

    public function getTrashMenu(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        Menu::where('id', $id)->update(['is_trashed' => 1]);

        return redirect($this->prefixUrl . $qs);
    }

    public function getRestoreMenu(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        Menu::where('id', $id)->update(['is_trashed' => 0, 'status' => 0]);

        return redirect($this->prefixUrl . '/trashed' . $qs);
    }

    public function getDeleteMenu(Request $request, $id=null){

        $qs = Vii::queryStringBuilder($request->getQueryString());

        Menu::destroy($id);//News::where('votes', '>', 100)->delete();

        MenuCache::where('menu_id', $id)->delete();

        MenuItem::where('menu_id', $id)->delete();

        return redirect($this->prefixUrl . '/trashed' . $qs);
    }



    /*-------------------------------------------------------------*/
    private function _retrieveMenuHierarchy($parent, &$item_data){
        if(!isset($parent['children']))
            return false;

        $children = $parent['children'];

        foreach($children as $k => &$child){
            $item_data[$child['id']]['ordering'] = $k + 1;
            $item_data[$child['id']]['parent_id'] = $parent['id'];
            $this->_retrieveMenuHierarchy($child, $item_data);
        }

        return true;
    }

    private function _renderMenuItemList($menu_data){

        if(empty($menu_data))
            return '';

        $html = '';
        foreach($menu_data as $k => $item){
            $html .= '<li id="menu_item_'.$item->id.'" class="dd-item" data-id="'.$item->id.'">';
            $html .= $this->_createItemBody($item);
            if(!empty($item->item_children)){
                $html .= $this->_renderChildrenMenuItem($item->item_children, $item->id);
            }

            $html .= '</li>';
        }

        return '<ol class="dd-list">' . $html . '</ol>';

    }

    private function _renderChildrenMenuItem($children, $parent_id){

        if(empty($children))
            return '';

        $html = '';
        foreach($children as $k => $item){

            $html .= '<li id="menu_item_'.$item->id.'" class="dd-item" data-id="'.$item->id.'">';
            $html .= $this->_createItemBody($item);

            if(!empty($item->item_children)){
                $html .= $this->_renderChildrenMenuItem($item->item_children, $item->id);
            }

            $html .= '</li>';
        }

        if($html != '')
            return '<ol class="dd-list">' . $html . '</ol>';

        return $html;
    }

    private function _createItemBody($item){

        $html = '';
        $html .= '   <div class="dd-handle dd4-handle">';
        $html .= '       <span id="menu_title_'.$item->id.'" class="menu-title">'.$item->item_name.'</span>';
        if($item->cat_id == 0){
            $html .= '       <span class="pull-right menu-type" style="margin-right: 25px;">Custom URL</span>';
        }
        else{
            $html .= '       <span class="pull-right menu-type" style="margin-right: 25px;">Category</span>';
        }
        $html .= '   </div>';

        $html .= '  <a id="btn_toggle_form_'.$item->id.'" href="#" class="dd4-btn-toggle" data-toggle-state="0"><i class="fa fa-caret-right"></i></a>';

        $html .= '  <div id="menu_item_form_'.$item->id.'" class="container-fluid menu-item-form" style="display: none;">';
        $html .= '      <div class="form-group">';
        $html .= '          <label for="item_name">Tên Menu</label>';
        $html .=            Form::text('item_name[]', $item->item_name, ['id'=>'item_name', 'class' => 'form-control input-sm dd4-item-name', 'placeholder' => 'Required', 'data-menu-title'=>'menu_title_'.$item->id]);
        $html .= '      </div>';

        if($item->cat_id != 0){
            $html .= '  <div class="form-group">';
            $html .= '      <label for="item_name">Danh Mục URL</label>';
            $_url = config('app.url') . '/' . $item->alias;
            $html .= '      <p class="form-control-static"><a href="'.$_url.'">'.$_url.'</a></p>';
            $html .= '  </div>';
        }

        if($item->custom_url == ''){
            $html .= '  <div class="form-group" style="display: none;">';
        }
        else{
            $html .= '  <div class="form-group">';
        }

        $html .= '          <label for="external_url">Custom URL</label>';
        $html .=            Form::text('custom_url[]', $item->custom_url, ['id'=>'custom_url', 'class' => 'form-control input-sm', 'placeholder' => 'Required']);
        $html .= '      </div>';

        $html .= '      <div class="form-group text-right">';
        $html .= '          <button type="button" class="btn btn-xs btn-flat bg-red btn-delete-menu-item" data-menu-item-id="menu_item_'.$item->id.'" data-menu-id="'.$item->id.'">Xóa</button>';
        $html .= '          <button type="button" class="btn btn-xs btn-flat bg-orange btn-close-menu-item" data-btn-toggle-form-id="btn_toggle_form_'.$item->id.'">Đóng</button>';
        $html .= '      </div>';

        $html .=        Form::hidden('item_id[]', $item->id);

        $html .= '  </div>';

        return $html;

    }





}