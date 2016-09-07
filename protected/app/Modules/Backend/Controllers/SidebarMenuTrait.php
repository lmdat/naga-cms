<?php   namespace App\Modules\Backend\Controllers;

use App\Libs\Utils\Vii;
use Request;

trait SidebarMenuTrait{



    public function createMenu(){
        $lang_mod = 'backend/sidebar';

        $menu = [
            [
                'id' => '1',
                'text' => trans($lang_mod . '.account_management'),
                'url' => '#',
                'params' => [],
                'icon' => "<i class='fa fa-shield' aria-hidden='true'></i>",
                'level-icon' => "<i class='fa fa-angle-left pull-right' aria-hidden='true'></i>",
                'children' => [
                    [
                        'id' => '11',
                        'text' => trans($lang_mod . '.account_list'),
                        'url' => 'accounts',//'admin/users',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right' aria-hidden='true'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    
//                    [
//                        'id' => '12',
//                        'text' => 'Roles &amp; Permissions',
//                        'url' => '#',//'admin/user/create',
//                        'params' => [],
//                        'icon' => "<i class='fa fa-check-square-o' aria-hidden='true'></i>",
//                        'level-icon' => "",
//                        'children' => []
//                    ]
                ]
            ],

            [
                'id' => '2',
                'text' => trans($lang_mod . '.cat_management'),
                'url' => '#',
                'params' => [],
                'icon' => "<i class='fa fa-folder-open-o' aria-hidden='true'></i>",
                'level-icon' => "<i class='fa fa-angle-left pull-right' aria-hidden='true'></i>",
                'children' => [
                    [
                        'id' => '21',
                        'text' => trans($lang_mod . '.cat_list'),
                        'url' => 'category',//'admin/users',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right' aria-hidden='true'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '22',
                        'text' => trans($lang_mod . '.add_cat'),
                        'url' => 'category/create',//'admin/users',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right' aria-hidden='true'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],

//                    [
//                        'id' => '12',
//                        'text' => 'Roles &amp; Permissions',
//                        'url' => '#',//'admin/user/create',
//                        'params' => [],
//                        'icon' => "<i class='fa fa-check-square-o' aria-hidden='true'></i>",
//                        'level-icon' => "",
//                        'children' => []
//                    ]
                ]
            ],
            
            [
                'id' => '3',
                'text' => trans($lang_mod . '.news_management'),
                'url' => '#',
                'params' => [],
                'icon' => "<i class='fa fa-newspaper-o'></i>",
                'level-icon' => "<i class='fa fa-angle-left pull-right' aria-hidden='true'></i>",
                'children' => [
                    [
                        'id' => '31',
                        'text' => trans($lang_mod . '.news_list'),
                        'url' => 'news',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '32',
                        'text' => trans($lang_mod . '.create_news'),
                        'url' => 'news/create',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '33',
                        'text' => trans($lang_mod . '.trashed_news_list'),
                        'url' => 'news/trashed',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '34',
                        'text' => trans($lang_mod . '.home_highlight_list'),
                        'url' => 'news/home-highlight',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '35',
                        'text' => trans($lang_mod . '.cat_highlight_list'),
                        'url' => 'news/cat-highlight',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
//                    [
//                        'id' => '24',
//                        'text' => 'Result Sheet',
//                        'url' => 'admin/lottery/result-sheets',
//                        'params' => [],
//                        'icon' => "<i class='fa fa-wpforms'></i>",
//                        'level-icon' => "",
//                        'children' => []
//                    ],
//                    [
//                        'id' => '24',
//                        'text' => 'Result Setting',
//                        'url' => 'admin/lottery/result-settings',
//                        'params' => [],
//                        'icon' => "<i class='fa fa-cog'></i>",
//                        'level-icon' => "",
//                        'children' => []
//                    ]
                ]
            ],

            [
                'id' => '4',
                'text' => trans($lang_mod . '.ads_management'),
                'url' => '#',
                'params' => [],
                'icon' => "<i class='fa fa-hand-pointer-o' aria-hidden='true'></i>",
                'level-icon' => "<i class='fa fa-angle-left pull-right' aria-hidden='true'></i>",
                'children' => [
                    [
                        'id' => '41',
                        'text' => trans($lang_mod . '.ads_list'),
                        'url' => 'ads',//'admin/users',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right' aria-hidden='true'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],

                    [
                        'id' => '42',
                        'text' => trans($lang_mod . '.ads_trashed_list'),
                        'url' => 'ads/trashed',//'admin/user/create',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right' aria-hidden='true'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],

                    [
                        'id' => '43',
                        'text' => trans($lang_mod . '.create_ads_position'),
                        'url' => 'ads/position/create',//'admin/user/create',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right' aria-hidden='true'></i>",
                        'level-icon' => "",
                        'children' => []
                    ]
                ]
            ],

            [
                'id' => '5',
                'text' => trans($lang_mod . '.menu_management'),
                'url' => '#',
                'params' => [],
                'icon' => "<i class='fa fa-list'></i>",
                'level-icon' => "<i class='fa fa-angle-left pull-right'></i>",
                'children' => [
                    [
                        'id' => '51',
                        'text' => trans($lang_mod . '.menu_list'),
                        'url' => 'menu',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '52',
                        'text' => trans($lang_mod . '.menu_trashed_list'),
                        'url' => 'menu/trashed',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '53',
                        'text' => trans($lang_mod . '.add_menu'),
                        'url' => 'menu/create',
                        'params' => [],
                        'icon' => "<i class='fa fa-angle-double-right'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],


                ]
            ],
            
            [
                'id' => '50',
                'text' => 'Media',
                'url' => '#',
                'params' => [],
                'icon' => "<i class='fa fa-magic'></i>",
                'level-icon' => "<i class='fa fa-angle-left pull-right'></i>",
                'children' => [
                    [
                        'id' => '501',
                        'text' => 'All things',
                        'url' => '#',//'admin/media/',
                        'params' => [],
                        'icon' => "<i class='fa fa-circle-o'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '502',
                        'text' => 'Images',
                        'url' => '#',//'admin/media/images',
                        'params' => [],
                        'icon' => "<i class='fa fa-picture-o'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '503',
                        'text' => 'Video',
                        'url' => '#',//'admin/media/video',
                        'params' => [],
                        'icon' => "<i class='fa fa-film'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '504',
                        'text' => 'Audio',
                        'url' => '#',//'admin/media/audio',
                        'params' => [],
                        'icon' => "<i class='fa fa-music'></i>",
                        'level-icon' => "",
                        'children' => []
                    ],
                    [
                        'id' => '504',
                        'text' => 'Documents',
                        'url' => '#',//'admin/media/document',
                        'params' => [],
                        'icon' => "<i class='fa fa-file-text-o'></i>",
                        'level-icon' => "",
                        'children' => []
                    ]
                    
                ]
            ],
                        
            [
                'id' => '6',
                'text' => 'Settings',
                'url' => 'setting',//'admin/setting',
                'params' => [],
                'icon' => "<i class='fa fa-gears'></i>",
                'level-icon' => "",
                'children' => []
            ]
            
            
        ];
        
        return $this->renderMenu($menu);
    }
    
    protected function renderMenu($menu=[]){
        $html = '';
        
        if(!empty($menu)){
            foreach($menu as $k => $item){
                //$item['id'] = strval($k+1);
                $pid = $k + 1;
                $item['id'] = hash('crc32', $pid);
                $pa = '';
                $child = $this->childMenu($item['children'], $pid, $pa, 1);
                if($child == ''){
                    $query_string = Vii::queryStringBuilder(['mid' => $item['id']]);
                    $_active = '';
                    if(strval(Request::input('mid')) == $item['id']){
                        $_active = 'active';
                    }
                    $html .= "<li class='$_active'>";
                    $html .= "<a href='".url($item['url'], $item['params']) . $query_string . "'>";
                    $html .= $item['icon'] . "<span>" . $item['text'] . "</span>";
                    $html .= "</a>";
                    $html .= "</li>";
                }
                else{
                    $html .= "<li class='treeview $pa'>";
                    $html .= "<a href='".url($item['url'])."'>";
                    $html .= $item['icon'] . "<span>" . $item['text'] . "</span>" . $item['level-icon'];
                    $html .= "</a>";
                    $html .= $child;
                    $html .= "</li>";
                }
            }
        }
        
        return $html;
        
    }
    
    protected function childMenu($children=[], $parent_id, &$parent_active='', $h=1){
        if(empty($children))
            return '';
        
        $html = '';
        foreach($children as $k => $item){
            //$item['id'] = $parent_id . ($k+1);
            $cid = $parent_id . ($k + 1);
            $item['id'] = hash('crc32', $cid);
            $query_string = '';
            if(empty($item['children']))
                $query_string = Vii::queryStringBuilder(['mid' => $item['id']]);
            
            $_active = '';
            if(strval(Request::input('mid')) == $item['id']){
                $_active = 'active';
                $parent_active = 'active';
            }
            $pa = '';
            $html .= "<li class='$_active'>";
            $html .= "<a href='".url($item['url'], $item['params']) . $query_string . "'>";
            $html .= $item['icon'] . "<span>" . $item['text'] . "</span>";
            if(!empty($item['children']))
                $html .= $item['level-icon'];
            $html .= "</a>";
            $html .= $this->childMenu($item['children'], $cid, $pa, $h + 1);
            $html .= "</li>";
        }
        
        return "<ul class='treeview-menu $pa'>$html</ul>";
    }
}