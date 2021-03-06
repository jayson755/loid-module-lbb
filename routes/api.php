<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* 注册 */
Route::post('register.html', Loid\Module\Lbb\Api\User::class . '@register')->name('api.register');

/* 获取验证码 */
Route::post('mobilecode.html', Loid\Module\Lbb\Api\MobileCode::class . '@getCode')->name('api.mobilecode');

/*获取业务分类*/
Route::post('category.html', Loid\Module\Lbb\Api\Category::class . '@getlist');

/*获取业务分类链接*/
Route::post('categoryurl/{category}.html', Loid\Module\Lbb\Api\Category::class . '@getUrl');

/*获取理财产品*/
Route::post('financial.html', Loid\Module\Lbb\Api\Financial::class . '@getlist');


Route::group(['prefix'=>'content'], function () {
    /* 获取公告 */
    Route::post('notice.html', Loid\Module\Lbb\Api\Content::class . '@notice');
    /* 获取关于我们 */
    Route::post('aboutus.html', Loid\Module\Lbb\Api\Content::class . '@aboutus');
    /* 获取banner */
    Route::post('banner.html', Loid\Module\Lbb\Api\Content::class . '@banner');
});    

/* 登录 */
Route::middleware(\Illuminate\Session\Middleware\StartSession::class)->post('signin.html', Loid\Module\Lbb\Api\User::class . '@signin');

/* 登出 */
Route::middleware(\Illuminate\Session\Middleware\StartSession::class)->post('logout.html', Loid\Module\Lbb\Api\User::class . '@logout');

Route::group(['prefix'=>'store', 'middleware'=>[\Illuminate\Session\Middleware\StartSession::class, \Loid\Module\Lbb\Middleware\Authentication::class]], function () {
    
    /* 提现 */
    Route::post('withdrawing.html', Loid\Module\Lbb\Api\Store::class . '@withdrawing');
    
    /* 充值 */
    Route::post('recharge.html', Loid\Module\Lbb\Api\Store::class . '@recharge');
    
    /* 购买理财产品 */
    Route::post('buy/financial.html', Loid\Module\Lbb\Api\Financial::class . '@buy');
});

Route::group(['prefix'=>'financial', 'middleware'=>[\Illuminate\Session\Middleware\StartSession::class, \Loid\Module\Lbb\Middleware\Authentication::class]], function () {
    /* 购买理财产品 */
    Route::post('buy.html', Loid\Module\Lbb\Api\Financial::class . '@buy');
});



Route::group(['prefix'=>'my', 'middleware'=>[\Illuminate\Session\Middleware\StartSession::class, \Loid\Module\Lbb\Middleware\Authentication::class]], function () {
    
    //修改密码
    Route::post('change/password.html', Loid\Module\Lbb\Api\My::class . '@changePassword');
    
    //修改支付密码
    Route::post('change/paypassword.html', Loid\Module\Lbb\Api\My::class . '@changePayPassword');
    
    /* 我的理财产品 */
    Route::post('financial.html', Loid\Module\Lbb\Api\My::class . '@financial');
    
    /* 我的理财产品详情 */
    Route::post('financial/{id}.html', Loid\Module\Lbb\Api\My::class . '@financialDetial');
    
    /*我的库存*/
    Route::post('store.html', Loid\Module\Lbb\Api\My::class . '@store');
    /*我的推广连接*/
    Route::post('promotelinks.html', Loid\Module\Lbb\Api\My::class . '@promoteLinks');
    
    /*我推广用户*/
    Route::post('promote.html', Loid\Module\Lbb\Api\My::class . '@promote');
    
    /*我的收支记录*/
    Route::post('balancerecord/{category}/{page}.html', Loid\Module\Lbb\Api\My::class . '@balancerecord');
    
});

Route::group(['prefix'=>'user', 'middleware'=>[\Illuminate\Session\Middleware\StartSession::class, \Loid\Module\Lbb\Middleware\Authentication::class]], function () {
    /*用户的推广用户*/
    Route::post('promote/{user}.html', Loid\Module\Lbb\Api\User::class . '@promote');
});
