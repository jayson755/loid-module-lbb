<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;

use Loid\Module\Lbb\Controllers\Controller;
use Loid\Module\Lbb\Logic\Store as StoreLogic;
use DB;

class StoreRechargeController extends Controller{
    
    public function index(){
        return $this->view("{$this->view_prefix}/store/recharge/index");
    }
    
    public function _getList(Request $request, $type){
        $list = \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_store_recharge')->whereNull('deleted_at')])->query();
        foreach ($list['rows'] as $key => $val) {
            $list['rows'][$key]['user'] = DB::table('lbb_user')->where('lbb_user_id', $val['user_id'])->value('lbb_user_account');
            $list['rows'][$key]['category'] = DB::table('lbb_category')->where('category_id', $val['store_category'])->value('category_name');
        }
        return $list;
    }
    
    /**
     * 充值申请处理
     */
    public function dealwith(Request $request){
        try {
            (new StoreLogic)->dealwithRecharge($request->input('recharge_id', 0));
            return $this->response(true);
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
    }
    
    /**
     * 删除充值申请
     */
    public function delete(Request $request){
        try {
            $storeRecharge = \Loid\Module\Lbb\Model\StoreRecharge::find($request->input('recharge_id'));
            if (empty($storeRecharge) || $storeRecharge->recharge_status == 1 || $storeRecharge->trashed()) {
                throw new \Exception('该申请不存在或已处理');
            }
            $storeRecharge->delete();
            return $this->response(true);
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
    }
}