<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;

use Loid\Frame\Controllers\Controller;
use DB;

class StoreController extends Controller{
    
    private $moudle = 'loid-module-lbb';
    
    public function __construct(){
        parent::__construct();
        
        if ($moudle = DB::table('system_support_moudle')->where('moudle_sign', $this->moudle)->first()) {
            $this->view_prefix = $moudle->view_namespace . '::' . config('view.default.theme') . DIRECTORY_SEPARATOR;
        }
    }
    
    public function index(){
        return $this->view("{$this->view_prefix}/store/index", [
            'rows' => $this->rows,
            'view_prefix' => $this->view_prefix
        ]);
    }
    
    public function _getList($type){
        $list = \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_store')])->query();
        foreach ($list['rows'] as $key => $val) {
            $list['rows'][$key]['user'] = DB::table('lbb_user')->where('lbb_user_id', $val['user_id'])->value('lbb_user_account');
            $list['rows'][$key]['category'] = DB::table('lbb_category')->where('category_id', $val['store_category'])->value('category_name');
        }
        return $list;
    }
}