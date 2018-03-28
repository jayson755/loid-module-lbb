<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;

use Loid\Module\Lbb\Controllers\Controller;
use DB;

class UserFinancialController extends Controller{
    
    public function index(){
        return $this->view("{$this->view_prefix}/user/financial/index", [
            'userJson' => DB::table('lbb_user')->select('lbb_user_id as id','lbb_user_account as title')->get()->toJson()
        ]);
    }
    
    public function _getList(Request $request, $type){
        return \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_user_financial')])->query();
    }
    
    public function modify(Request $request){
        try {
            if ($request->input('oper') == 'add' && empty($request->input('category_id'))) {
                (new FinancialLogic)->add($request->all());
            } else {
                (new FinancialLogic)->modify($request->all());
            }
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
        return $this->response(true);
    }
}