<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;
use Loid\Module\Lbb\Controllers\Controller;
use Loid\Module\Lbb\Logic\Store as StoreLogic;
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
    
    public function modify(Request $request){
        try {
            $store = \Loid\Module\Lbb\Model\Store::find($request->input('store_id'));
            if (empty($store)) {
                throw new \Exception('²Ö¿â²»´æÔÚ');
            }
            (new StoreLogic)->event(\Loid\Module\Lbb\Model\LbbUser::find($store->user_id), $store->store_category, $request->input('store_num'));
            return $this->response(true);
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
    }
}