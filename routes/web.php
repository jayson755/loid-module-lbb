<?php


Route::group(['prefix'=>'manage', 'middleware'=>['web', 'auth', \Loid\Frame\Middleware\MoudleInit::class]], function () {
    
    /*用户管理*/
    Route::get('lbb/user.html', Loid\Module\Lbb\Controllers\UserController::class.'@index')->name('lbb.user');
    Route::get('lbb/user/list/{param}.html', Loid\Module\Lbb\Controllers\UserController::class . '@getjQGridList')->name('lbb.user.list');
    Route::post('role/modify.html', Loid\Module\Lbb\Controllers\UserController::class . '@modify')->name('lbb.user.modify');
    
    
    /*仓库管理*/
    Route::get('lbb/store.html', Loid\Module\Lbb\Controllers\StoreController::class.'@index')->name('lbb.store');
    Route::get('lbb/store/list/{param}.html', Loid\Module\Lbb\Controllers\StoreController::class . '@getjQGridList')->name('lbb.store.list');
    
    /*充值管理*/
    Route::get('lbb/store/recharge.html', Loid\Module\Lbb\Controllers\StoreRechargeController::class.'@index')->name('lbb.store.recharge');
    Route::get('lbb/store/recharge/list/{param}.html', Loid\Module\Lbb\Controllers\StoreRechargeController::class . '@getjQGridList')->name('lbb.store.recharge.list');
    Route::post('lbb/store/recharge/dealwith.html', Loid\Module\Lbb\Controllers\StoreRechargeController::class . '@dealwith')->name('lbb.store.recharge.dealwith');
    
    
    /*提现管理*/
    Route::get('lbb/store/withdrawing.html', Loid\Module\Lbb\Controllers\StoreWithdrawingController::class.'@index')->name('lbb.store.withdrawing');
    Route::get('lbb/store/withdrawing/list/{param}.html', Loid\Module\Lbb\Controllers\StoreWithdrawingController::class . '@getjQGridList')->name('lbb.store.withdrawing.list');
    Route::post('lbb/store/withdrawing/dealwith.html', Loid\Module\Lbb\Controllers\StoreWithdrawingController::class . '@dealwith')->name('lbb.store.withdrawing.dealwith');
    
    
    /*充值管理*/
    Route::get('lbb/category.html', Loid\Module\Lbb\Controllers\CategoryController::class.'@index')->name('lbb.category');
    Route::get('lbb/category/list/{param}.html', Loid\Module\Lbb\Controllers\CategoryController::class . '@getjQGridList')->name('lbb.category.list');
    Route::post('lbb/category/modify.html', Loid\Module\Lbb\Controllers\CategoryController::class . '@modify')->name('lbb.category.modify');
    
    /*理财管理*/
    Route::get('lbb/financial.html', Loid\Module\Lbb\Controllers\FinancialController::class.'@index')->name('lbb.financial');
    Route::get('lbb/financial/list/{param}.html', Loid\Module\Lbb\Controllers\FinancialController::class . '@getjQGridList')->name('lbb.financial.list');
    Route::post('lbb/financial/modify.html', Loid\Module\Lbb\Controllers\FinancialController::class . '@modify')->name('lbb.financial.modify');
});