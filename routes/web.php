<?php

use Illuminate\Http\Request;

Route::group(['prefix'=>'manage', 'middleware'=>['web', 'auth', \Loid\Frame\Middleware\MoudleInit::class]], function () {
    
    /*用户管理*/
    Route::get('lbb/user', Loid\Module\Lbb\Controllers\UserController::class.'@index')->name('lbb.user');
    Route::get('lbb/user/list/{param}', Loid\Module\Lbb\Controllers\UserController::class . '@getjQGridList')->name('lbb.user.list');
    Route::post('role/modify', Loid\Module\Lbb\Controllers\UserController::class . '@modify')->name('lbb.user.modify');
    
    
    /*仓库管理*/
    Route::get('lbb/store', Loid\Module\Lbb\Controllers\StoreController::class.'@index')->name('lbb.store');
    Route::get('lbb/store/list/{param}', Loid\Module\Lbb\Controllers\StoreController::class . '@getjQGridList')->name('lbb.store.list');
    
    /*充值管理*/
    Route::get('lbb/store/recharge', Loid\Module\Lbb\Controllers\StoreRechargeController::class.'@index')->name('lbb.store.recharge');
    Route::get('lbb/store/recharge/list/{param}', Loid\Module\Lbb\Controllers\StoreRechargeController::class . '@getjQGridList')->name('lbb.store.recharge.list');
    Route::post('lbb/store/recharge/dealwith', Loid\Module\Lbb\Controllers\StoreRechargeController::class . '@dealwith')->name('lbb.store.recharge.dealwith');
    
    
    /*提现管理*/
    Route::get('lbb/store/withdrawing', Loid\Module\Lbb\Controllers\StoreWithdrawingController::class.'@index')->name('lbb.store.withdrawing');
    Route::get('lbb/store/withdrawing/list/{param}', Loid\Module\Lbb\Controllers\StoreWithdrawingController::class . '@getjQGridList')->name('lbb.store.withdrawing.list');
    Route::post('lbb/store/withdrawing/dealwith', Loid\Module\Lbb\Controllers\StoreWithdrawingController::class . '@dealwith')->name('lbb.store.withdrawing.dealwith');
    
    
    /*充值管理*/
    Route::get('lbb/category', Loid\Module\Lbb\Controllers\CategoryController::class.'@index')->name('lbb.category');
    Route::get('lbb/category/list/{param}', Loid\Module\Lbb\Controllers\CategoryController::class . '@getjQGridList')->name('lbb.category.list');
    Route::post('lbb/category/modify', Loid\Module\Lbb\Controllers\CategoryController::class . '@modify')->name('lbb.category.modify');
    
    /*理财产品管理*/
    Route::get('lbb/financial', Loid\Module\Lbb\Controllers\FinancialController::class.'@index')->name('lbb.financial');
    Route::get('lbb/financial/list/{param}', Loid\Module\Lbb\Controllers\FinancialController::class . '@getjQGridList')->name('lbb.financial.list');
    Route::post('lbb/financial/modify', Loid\Module\Lbb\Controllers\FinancialController::class . '@modify')->name('lbb.financial.modify');
    
    /*用户理财管理*/
    Route::get('lbb/user/financial', Loid\Module\Lbb\Controllers\UserFinancialController::class.'@index')->name('lbb.user.financial');
    
    Route::get('lbb/user/financial/list/{param?}', function (Request $request, $param = null) {
        return (new \Loid\Module\Lbb\Controllers\UserFinancialController)->getjQGridList($request, $param);
    })->name('lbb.user.financial.list');
});