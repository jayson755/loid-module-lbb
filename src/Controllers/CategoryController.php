<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;
use Loid\Module\Lbb\Controllers\Controller;
use Loid\Module\Lbb\Logic\Category as CategoryLogic;
use DB;

class CategoryController extends Controller{
    
    public function index(){
        return $this->view("{$this->view_prefix}/category/index");
    }
    
    public function _getList(Request $request, $type){
        return \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_category')])->query();
    }
    
    public function modify(Request $request){
        try {
            if ($request->input('oper') == 'add' && empty($request->input('category_id'))) {
                (new CategoryLogic)->add($request->all());
            } else {
                (new CategoryLogic)->modify($request->all());
            }
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
        return $this->response(true);
    }
}