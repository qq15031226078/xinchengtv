<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
 */
$api = app('Dingo\Api\Routing\Router');

// add in header    Accept:application/vnd.lumen.pc+json
$api->version('pc', ['namespace' => 'App\Http\Controllers\Api\V1'], function ($api) {
    $api->get('getcarousel', 'IndexController@getCarousel');//pc轮播
    $api->get('getindexlist', 'IndexController@getIndexList');  //首页新闻
    $api->get('getindustry', 'IndexController@getIndustry');  //左边导航
    $api->get('gethomeprogramm', 'IndexController@GetHomeProgramm');  //首页节目
    $api->get('getxiangsulist', 'IndexController@getXiangsuList');//当前 像素科技 新城商业播放地址
    $api->get('gettvlist', 'IndexController@getTvList'); //当前新城tv 播放地址

});

// add in header    Accept:application/vnd.lumen.v1+json
$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api\V1',
], function ($api) {
    // Auth
    // login
    $api->post('authorizations', [
        'as' => 'authorizations.store',
        'uses' => 'AuthController@store',
    ]);
    // User
    $api->post('users', [
        'as' => 'users.store',
        'uses' => 'UserController@store',
    ]);
    // user list

    $api->get('users', [
        'as' => 'users.index',
        'uses' => 'UserController@index',
    ]);
    // user detail
    $api->get('users/{id}', [
        'as' => 'users.show',
        'uses' => 'UserController@show',
    ]);
    $api->put('authorizations/current', [
        'as' => 'authorizations.update',
        'uses' => 'AuthController@update',
    ]);
    $api->get('getgallery', 'NewsController@getGallery');    //获取图库信息
    $api->get('getnewsindex', 'NewsController@GetNewsIndex');  //新闻内容
    $api->get('getnewsedge', 'NewsController@GetnewsEdge');//新闻边上的信息


    $api->get('getindexlatestinfo', 'IndexController@getIndexLatestInfo');   //获取首页最新资讯
    $api->get('getxinchengindexlatestinfo', 'IndexController@getXinchengIndexLatestInfo');   //获取首页新城商业
    $api->get('getxinchengpromotelist', 'IndexController@getXinchengPromoteList');   //获取播放列表新城商业接口

    $api->get('getxinchengtvindexlatestinfo', 'IndexController@getXinchengTVIndexLatestInfo');   //获取首页新城TV
    $api->get('getxinchengtvpromotelist', 'IndexController@getXinchengTVPromoteList'); //获取播放列表新城TV接口

    $api->get('getxiangsuindexlatestinfo', 'IndexController@getXiangsuIndexLatestInfo');   //获取首页像素科技
    $api->get('getxiangsupromotelist', 'IndexController@getXiangsuPromoteList');   //获取播放列表像素科技接口

    $api->get('getindexinfolist', 'IndexController@getIndexInfoList');    // 和单个行业最新资讯
    $api->get('getindustryindexinfolist', 'IndexController@getIndustryIndexInfoList'); //获取父级下的行业最新资讯
    $api->get('getproductlist', 'IndexController@getProductList');   //获取产品列表
    $api->get('getindustryproductlist', 'IndexController@getIndustryProductList');   //获取父级下的产品列表
    $api->get('gettechlist', 'IndexController@getTechList');    //获取科技列表
    $api->get('getindustrytechlist', 'IndexController@getIndustryTechList');    //获取父级下的科技列表
    $api->get('getprojectlist', 'IndexController@getProjectList'); // 获取项目列表
    $api->get('coreinfoblock', 'IndexController@getCoreInfoBlock');  //产品科技项目
    $api->get('keywordinfoblock', 'IndexController@keywordInfoBlock'); //关键词信息块
    $api->get('getindustryprojectlist', 'IndexController@getIndustryProjectList'); // 获取父级下的项目列表
    $api->get('getcomlist', 'IndexController@getComList');// 获取公司列表
    $api->get('getindustrycomlist', 'IndexController@getIndustryComList');// 获取父级下的公司列表
    $api->get('getsetuplist', 'IndexController@getSetupList');//获取机构列表
    $api->get('getindustrysetuplist', 'IndexController@getIndustrySetupList');//获取父级下的机构列表
    $api->get('getcelebritylist', 'IndexController@getCelebrityList');//获取人物列表
    $api->get('getindustrycelebritylist', 'IndexController@getIndustryCelebrityList');//获取父级下的人物列表

    $api->get('getoenindustry', 'IndexController@getOenIndustry');//左边导航一级
    $api->get('gettwoindustry', 'IndexController@getTwoIndustry');//左边导航二级
    $api->get('getallnewslist', 'NewsController@getAllNewsList');
    $api->post('uploadfile', 'UploadsController@UploadFile');
    $api->get('pushtest', 'PushController@pushTest');
    // need authentication
    $api->group(['middleware' => 'api.auth'], function ($api) {
        $api->delete('authorizations/current', [
            'as' => 'authorizations.destroy',
            'uses' => 'AuthController@destroy',
        ]);
        // USER
        // my detail
        $api->get('user', [
            'as' => 'user.show',
            'uses' => 'UserController@userShow',
        ]);

        // update part of me
        $api->patch('user', [
            'as' => 'user.update',
            'uses' => 'UserController@patch',
        ]);
        // update my password
        $api->put('user/password', [
            'as' => 'user.password.update',
            'uses' => 'UserController@editPassword',
        ]);
    });
});



$app->group(['prefix'=>'v2', 'namespace'=>'Api\V2'], function () use ($app) {

    $app->get('home', 'HomeController@index');//首页(彦平)
    $app->get('home/channel', 'HomeController@channel');//频道首页(彦平)
    /* 频道导航 */
    $app->get('navigation', 'IndustryController@navigation');
    /* 新闻详情 */
    $app->get('news/details', 'NewsController@getnewsdetails');
    /* 新闻缩略图（分享） ———— 朱朔男 */
    $app->get('news/share/{draft_id}', 'NewsController@getnewsthumb');
    /* 人物列表 */
    $app->get('keyword/people', 'PeopleController@keywordpeople');

    /* 节目 ———— 朱朔男 */
    $app->get('program', 'ProgramController@Program');

    /* 关键词:信息块 ———— 朱朔男 */
    $app->get('keyword/block/{keyword_code}', 'KeywordController@KeywordBlock');
    $app->get('keyword/news', 'KeywordController@KeywordNews'); //关键词:新闻(彦平)
    $app->get('search', 'KeywordController@search'); //搜索关键词或新闻(彦平)

    /* 关键词:图库列表 ———— 朱朔男 */
    $app->get('keyword/gallery/{keyword_code}', 'KeywordController@KeywordGallery');
    /* 关键词:进展 ———— 朱朔男 */
    $app->get('keyword/achievement/{keyword_code}', 'KeywordController@KeywordAchievement');

    /* 关键词：产科项单独介绍 ———— 朱朔男 */
    $app->get('keyword/core/{core_id}', 'KeywordController@KeywordCore');
    // 关键词:产科项 - 马骏飞
    $app->get('core', 'CorevalueController@getContentItem');
    // 关键词:公司、机构 - 马骏飞
    $app->get('company', 'CorevalueController@getContentItem');
    // 关键词:人物 - 马骏飞
    $app->get('people', 'CorevalueController@getContentItem');
    // 关键词:简介 - 马骏飞
    $app->get('keyword/intro', 'CorevalueController@KeywordIntro');

    $app->get('xcinfo', 'ExternalController@getxcinfo');

});



$app->group(['prefix'=>'pc/v2', 'namespace'=>'Api\V2\Pc'], function () use ($app) {
    $app->get('home', 'HomeController@index');

    // 首页：新闻列表
    $app->get('news/list', 'NewsController@lists');
    // 新闻详情
    $app->get('news/info', 'NewsController@infos');

    $app->get('slide', 'HomeController@slide');         //首页轮播图展示
    $app->get('program', 'HomeController@program');     //首页节目展示
    $app->get('program/list', 'ProgramController@program');     //首页节目展示

    // 分享节目
    $app->get('/getprogramfx', 'ProgramController@getprogramfx');

    // 关键词
    $app->get('/keyword/info', 'KeywordController@getkeywordinfo');

});

