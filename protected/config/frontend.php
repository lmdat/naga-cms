<?php
return [

    'media' => [

        'css' => [

            'vendors/font-awesome/css/font-awesome.min.css',
            'vendors/bootstrap/dist/css/bootstrap.min.css',
            'vendors/iCheck/skins/minimal/_all.css',
            'vendors/animate.css/animate.min.css',

            'css/google-font.css',
            'css/main.css',
            'css/pagination.css'
        ],

        'js' => [

            'vendors/jquery/dist/jquery.min.js',
            'vendors/bootstrap/dist/js/bootstrap.min.js',
            'vendors/iCheck/icheck.min.js',
            'vendors/moment/min/moment.min.js',
            'vendors/moment/locale/vi.js',
            'js/jquery.easing.1.3.js',
            'vendors/jquery-slimscroll/jquery.slimscroll.min.js',
            'vendors/scrollup/dist/jquery.scrollUp.min.js',
            'js/holder.min.js'

        ],

    ],

    'ROUTE_NAME' => [
        'home' => 'news-home',
        'category' => 'news-category',
        'detail_news' => 'news-detail',
        'detail_video' => 'video-detail'

    ],

    'lang' => 'vi',
    'start_year' => '2016',

    'TOTAL_ITEM_IN_CATE_BLOCK' => 4,
    'TOTAL_ITEM_IN_HIGHLIGHT_BLOCK' => 4,
    'TOTAL_ITEM_IN_HOT_BLOCK' => 15,
    'TOTAL_ITEM_IN_MOST_READ_BLOCK' => 15,

    'TOTAL_ITEM_PER_PAGE_IN_CATEGORY' => 20,
    'TOTAL_ITEM_PER_PAGE_IN_SEARCH' => 20,

];
