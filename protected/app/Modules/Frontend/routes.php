<?php

Route::group(
    [
        'module'=>'Frontend',
        'namespace'=>'App\Modules\Frontend\Controllers',
        'middleware' => ['web']
    ], function(){




    Route::get('/',
        [
            'as' => config('frontend.ROUTE_NAME.home'),
            'uses' => 'HomeController@getHome'
        ]
    );

    Route::match(['get', 'post'], 'search',
        [
            'as' => 'search',
            'uses' => 'HomeController@getSearchResult'
        ]
    );


    Route::get('{cat_alias}',
        [
            'as' => config('frontend.ROUTE_NAME.category'),
            'uses' => 'CategoryController@getCategory'
        ]
    )->where([
        'cat_alias' => '[0-9A-Za-z\-]+'
    ]);

    Route::get('{cat_alias}/{alias}-{id}.html',
        [
            'as' => config('frontend.ROUTE_NAME.detail_news'),
            'uses' => 'DetailController@getNewsDetail'
        ]
    )->where([
        'cat_alias' => '[0-9A-Za-z\-]+',
        'alias' => '[0-9A-Za-z\-]+',
        'id' => '[0-9]+' //'id','\d+'
    ]);




});