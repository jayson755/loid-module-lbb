<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;
use Loid\Module\Lbb\Controllers\Controller;
use DB;

class StoreController extends Controller{
    
    public function index(){
        return $this->view("{$this->view_prefix}/store/index");
    }
    
    public function _getList(Request $request, $type){
        $list = \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_store')])->query();
        foreach ($list['rows'] as $key => $val) {
            $list['rows'][$key]['user'] = DB::table('lbb_user')->where('lbb_user_id', $val['user_id'])->value('lbb_user_account');
            $list['rows'][$key]['category'] = DB::table('lbb_category')->where('category_id', $val['store_category'])->value('category_name');
        }
        return $list;
    }
}