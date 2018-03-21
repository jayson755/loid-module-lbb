<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;

use Loid\Frame\Controllers\Controller;
use Loid\Module\Lbb\Logic\Category as CategoryLogic;
use DB;

class CategoryController extends Controller{
    
    private $moudle = 'loid-module-lbb';
    
    public function __construct(){
        parent::__construct();
        
        if ($moudle = DB::table('system_support_moudle')->where('moudle_sign', $this->moudle)->first()) {
            $this->view_prefix = $moudle->view_namespace . '::' . config('view.default.theme') . DIRECTORY_SEPARATOR;
        }
    }
    
    public function index(){
        return $this->view("{$this->view_prefix}/category/index", [
            'rows' => $this->rows,
            'view_prefix' => $this->view_prefix
        ]);
    }
    
    public function _getList($type){
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