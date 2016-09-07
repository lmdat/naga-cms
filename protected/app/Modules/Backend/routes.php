<?php

Route::group(
    [
        'module'=>'Backend',
        //'prefix'=>'admin',
        'namespace'=>'App\Modules\Backend\Controllers',
        'middleware' => ['web']
    ], function(){

    Route::get('/test', function(){
        echo "Hello Admin";
    });

    Route::get('/', ['middleware' => ['admin_permission'], 'roles' => ['*'], 'uses' => 'DashboardController@welcome']);

    Route::get('/login', ['as' => 'admin.login', 'uses' => 'AuthController@getLogin']);

    Route::get('/logout', ['uses' => 'AuthController@getLogout']);

    Route::post('/login', ['uses' => 'AuthController@postLogin']);


    //Admin User
    Route::group(
        [
            'prefix' => 'accounts',
            'middleware' => ['admin_permission'],
            'roles' => [700, 500]
        ],
        function(){

            Route::get('/', ['uses' => 'AdminController@getUserList']);
            Route::get('/create', ['uses' => 'AdminController@getUserForm']);
            Route::post('/create', ['uses' => 'AdminController@postUserCreate']);

            Route::get('/edit/{id}', ['uses' => 'AdminController@getUserForm']);
            Route::put('/edit/{id}', ['uses' => 'AdminController@putUserEdit']);

            Route::get('/delete/{id}', ['uses' => 'AdminController@getDelete']);
    });

    //Category
    Route::group(
        [
            'prefix' => 'category',
            'middleware' => ['admin_permission'],
            'roles' => [700]
        ], function(){

        Route::match(['get', 'post'], '/', ['uses' => 'CategoryController@getCategoryList']);

        Route::get('/create', ['uses' => 'CategoryController@getCategoryForm']);

        Route::post('/create', ['uses' => 'CategoryController@postCreateCategory']);

        Route::get('/edit/{id}', ['uses' => 'CategoryController@getCategoryForm']);

        Route::put('/edit/{id}', ['uses' => 'CategoryController@putEditCategory']);

        Route::post('/ordering', ['uses' => 'CategoryController@postOrderingCategory']);

        Route::get('/published/{id}', ['uses' => 'CategoryController@getPublishedCategory']);

    });

    //News
    Route::group(
        [
            'prefix' => 'news',
            'middleware' => ['admin_permission'],
            'roles' => ['*']
        ], function(){

        Route::match(['get', 'post'], '/', ['uses' => 'NewsController@getNewsList']);

        Route::match(['get', 'post'], '/trashed', ['uses' => 'NewsController@getTrashedList']);

        Route::match(['get', 'post'], '/home-highlight', ['uses' => 'NewsController@getHomeHighlightList']);

        Route::match(['get', 'post'], '/cat-highlight', ['uses' => 'NewsController@getCatHighlightList']);


        Route::get('/create', ['uses' => 'NewsController@getNewsForm']);

        Route::post('/create', ['uses' => 'NewsController@postCreateNews']);

        Route::get('/edit/{id}', ['uses' => 'NewsController@getNewsForm']);

        Route::put('/edit/{id}', ['uses' => 'NewsController@putEditNews']);

        Route::get('/trash/{id}', ['uses' => 'NewsController@getTrashNews']);

        Route::get('/restore/{id}', ['uses' => 'NewsController@getRestoreNews']);

        Route::get('/delete/{id}', ['uses' => 'NewsController@getDeleteNews']);

        Route::get('/cancel/{action}', ['uses' => 'NewsController@getCancelNews']);


        Route::get('/published/{id}', ['uses' => 'NewsController@getPublishedNews']);

        Route::get('/home-highlight/remove/{id}', ['uses' => 'NewsController@getRemoveHomeHighlight']);

        Route::get('/cat-highlight/remove/{id}', ['uses' => 'NewsController@getRemoveCatHighlight']);


        Route::post('/upload-file', ['uses' => 'NewsController@postAjaxUploadFile']);
        Route::get('/media-list/{id}', ['uses' => 'NewsController@getAjaxMediaList']);

        Route::match(['get', 'post'],'/related-news', ['uses' => 'NewsController@postAjaxRelatedNews']);

    });

    //Ads
    Route::group(
        [
            'prefix' => 'ads',
            'middleware' => ['admin_permission'],
            'roles' => [700]
        ], function(){

        Route::match(['get', 'post'], '/', ['uses' => 'AdsController@getAdsList']);

        Route::match(['get', 'post'], '/trashed', ['uses' => 'AdsController@getTrashedList']);

        Route::get('/create', ['uses' => 'AdsController@getAdsForm']);

        Route::post('/create', ['uses' => 'AdsController@postCreateAds']);

        Route::get('/edit/{id}', ['uses' => 'AdsController@getAdsForm']);

        Route::put('/edit/{id}', ['uses' => 'AdsController@putEditAds']);

        Route::get('/trash/{id}', ['uses' => 'AdsController@getTrashAds']);

        Route::get('/restore/{id}', ['uses' => 'AdsController@getRestoreAds']);

        Route::get('/delete/{id}', ['uses' => 'AdsController@getDeleteAds']);

        Route::get('/published/{id}', ['uses' => 'AdsController@getPublishedAds']);


        Route::get('/position/create', ['uses' => 'AdsPositionController@getPositionForm']);

        Route::match(['post', 'put'], '/position/create', ['uses' => 'AdsPositionController@postCreatePosition']);

//        Route::get('/edit/{id}', ['uses' => 'CategoryController@getCategoryForm']);
//
//        Route::put('/edit/{id}', ['uses' => 'CategoryController@putEditCategory']);
//
//        Route::post('/ordering', ['uses' => 'CategoryController@postOrderingCategory']);
//


    });

    //Menu
    Route::group(
        [
            'prefix' => 'menu',
            'middleware' => ['admin_permission'],
            'roles' => [700]
        ], function(){

        Route::match(['get', 'post'], '/', ['uses' => 'MenuController@getMenuList']);

        Route::match(['get', 'post'], '/trashed', ['uses' => 'MenuController@getTrashedList']);

        Route::get('/create', ['uses' => 'MenuController@getMenuForm']);

        Route::post('/create', ['uses' => 'MenuController@postCreateMenu']);

        Route::get('/edit/{id}', ['uses' => 'MenuController@getMenuForm']);

        Route::put('/edit/{id}', ['uses' => 'MenuController@putEditMenu']);

        Route::post('/create-item', ['uses' => 'MenuController@postAjaxCreateMenuItem']);


        Route::get('/trash/{id}', ['uses' => 'MenuController@getTrashMenu']);

        Route::get('/restore/{id}', ['uses' => 'MenuController@getRestoreMenu']);

        Route::get('/delete/{id}', ['uses' => 'MenuController@getDeleteMenu']);

        Route::get('/published/{id}', ['uses' => 'MenuController@getPublishedMenu']);




    });

    //Setting
    Route::group(
        [
            'prefix' => 'setting',
            'middleware' => ['admin_permission'],
            'roles' => [700, 500]
        ],
        function(){

            Route::get('/', ['uses' => 'SettingController@getSettingForm']);
            Route::post('/', ['uses' => 'SettingController@postCreateSetting']);
//            Route::get('/create', ['uses' => 'AdminController@getUserForm']);
//            Route::post('/create', ['uses' => 'AdminController@postUserCreate']);
//
//            Route::get('/edit/{id}', ['uses' => 'AdminController@getUserForm']);
//            Route::put('/edit/{id}', ['uses' => 'AdminController@putUserEdit']);
//
//            Route::get('/delete/{id}', ['uses' => 'AdminController@getDelete']);
        });


});
