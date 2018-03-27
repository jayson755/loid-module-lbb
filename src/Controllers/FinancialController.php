<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;

use Loid\Module\Lbb\Controllers\Controller;
use Loid\Module\Lbb\Logic\Financial as FinancialLogic;
use Loid\Module\Lbb\Logic\Category as CategoryLogic;
use DB;

class FinancialController extends Controller{
   
    public function index(){
        return $this->view("{$this->view_prefix}/financial/index", [
            'categoryJson'=>(new CategoryLogic)->getCategoryList('on', ['category_id as id', 'category_name as title'])->toJson(),
            'limitJson' => json_encode(array_column(config('business.financial_limit'), 'date', 'index')),
        ]);
    }
    
    public function _getList(Request $request, $type){
        return \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_financial')])->query();
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