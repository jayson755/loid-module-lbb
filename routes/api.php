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
Route::post('register.html', Loid\Module\Lbb\Api\User::class . '@register');

/*获取业务分类*/
Route::post('category.html', Loid\Module\Lbb\Api\Category::class . '@getlist');

/*获取理财产品*/
Route::post('financial.html', Loid\Module\Lbb\Api\Financial::class . '@getlist');

/* 登录 */
Route::middleware(\Illuminate\Session\Middleware\StartSession::class)->post('signin.html', Loid\Module\Lbb\Api\User::class . '@signin');


Route::group(['prefix'=>'store', 'middleware'=>[\Illuminate\Session\Middleware\StartSession::class, \Loid\Module\Lbb\Middleware\Authentication::class]], function () {
    
    /*我的库存*/
    Route::post('user.html', Loid\Module\Lbb\Api\Store::class . '@getStoreByUser');
    
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
    
    /* 我的理财产品 */
    Route::post('my.html', Loid\Module\Lbb\Api\Financial::class . '@my');
});
