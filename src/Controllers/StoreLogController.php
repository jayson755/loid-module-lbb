<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;
use Loid\Module\Lbb\Controllers\Controller;
use Loid\Module\Lbb\Logic\Store as StoreLogic; 
use DB;

class StoreLogController extends Controller{
    
    public function index(){
        return $this->view("{$this->view_prefix}/store/log/index", [
            'userJson' => DB::table('lbb_user')->select('lbb_user_id as id','lbb_user_account as title')->get()->toJson(),
            'categoryJson' => DB::table('lbb_category')->select('category_id as id','category_name as title')->get()->toJson(),
            'flagJson' => json_encode((new StoreLogic)->getFlag())
        ]);
    }
    
    public function _getList(Request $request, $type){
        return \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_store_log')])->query();
    }
}