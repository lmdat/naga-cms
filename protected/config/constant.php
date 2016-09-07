<?php
return [

    'NEWS_DRAFT_STATUS' => -1,
    'NEWS_UNPUBLISHED_STATUS' => 0,
    'NEWS_PUBLISHED_STATUS' => 1,
    'NEWS_PENDING_STATUS' => 2,
    'NEWS_AUTO_PUBLISHED_STATUS' => 3,


    // 'ADS_UNPUBLISHED_STATUS' => 0,
    // 'ADS_PUBLISHED_STATUS' => 1,
    // 'ADS_OUT_OF_DATE_STATUS' => -1,

    
    'NEWS_STANDARD_TYPE' => 1,
    'NEWS_VIDEO_TYPE' => 2,

    'PAGE_POSITION' => [
        'HOME' => 1,
        'CATEGORY' => 2,
        'DETAIL' => 3
    ],

    'ADS_POSITION' => [
        'HOME_FIX' => [
            'HOME_RIGHT_ABOVE_MAINMENU' => 'home.right.above.mainmenu',
            'HOME_FULL_BELOW_MAINMENU' => 'home.full.below.mainmenu',
            'HOME_RIGHT_HIGHLIGHT' => 'home.right.highlight',
            'HOME_FULL_ABOVE_CATEGORY_SECTION' => 'home.full.above.category_section',
        ],

        'HOME_DYNAMIC' => [
            'HOME_RIGHT_CATEGORY_GROUP' => 'home.right.category_group',
            'HOME_FULL_BELOW_CATEGORY_GROUP' => 'home.full.below.category_group',
            
        ],

        'CATEGORY_FIX' => [
            'CATEGORY_RIGHT_ABOVE_MAINMENU' => 'category.right.above.mainmenu',
            'CATEGORY_FULL_BELOW_MAINMENU' => 'category.full.below.mainmenu',
            'CATEGORY_BELOW_MOST_READ' => 'category.below.most_read',
            'CATEGORY_RIGHT_BAR' => 'category.right.bar',
        ],

        'DETAIL_NEWS_FIX' => [
            'DETAIL_NEWS_RIGHT_ABOVE_MAINMENU' => 'detail.news.right.above.mainmenu',
            'DETAIL_NEWS_FULL_BELOW_MAINMENU_1' => 'detail.news.full.below.mainmenu_1',
            'DETAIL_NEWS_FULL_BELOW_MAINMENU_2' => 'detail.news.full.below.mainmenu_2',
            'DETAIL_NEWS_BELOW_RELATION' => 'detail.news.below.relation',
            'DETAIL_NEWS_RIGHT_ABOVE_HOT' => 'detail.news.right.above.hot',
            'DETAIL_NEWS_BELOW_MOST_READ' => 'detail.news.below.most_read',
            'DETAIL_NEWS_RIGHT_BAR' => 'detail.news.right.bar',
        ],

        'DETAIL_VIDEO_FIX' => [
            'DETAIL_VIDEO_RIGHT_ABOVE_MAINMENU' => 'detail.video.right.above.mainmenu',
            'DETAIL_VIDEO_RIGHT_BAR' => 'detail.video.right.bar',
            'DETAIL_VIDEO_BELOW_VIDEO' => 'detail.video.below.video',
        ],


    ],
    
    'MEMCACHE_TIMEOUT' => 10, //minutes
    'TOP_MENU_MEMCACHE_TIMEOUT' => 20, //minutes
    'ARTICLE_HIT_COUNTER_TIMEOUT' => 2, //minutes
    

];