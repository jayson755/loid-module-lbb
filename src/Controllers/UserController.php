<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;

use Loid\Frame\Controllers\Controller;
use DB;

class UserController extends Controller{
    
    private $moudle = 'loid-module-lbb';
    
    public function __construct(){
        parent::__construct();
        
        if ($moudle = DB::table('system_support_moudle')->where('moudle_sign', $this->moudle)->first()) {
            $this->view_prefix = $moudle->view_namespace . '::' . config('view.default.theme') . DIRECTORY_SEPARATOR;
        }
    }
    
    public function index(){
        return $this->view("{$this->view_prefix}/user/index", [
            'rows' => $this->rows,
            'view_prefix' => $this->view_prefix
        ]);
    }
    
    public function _getList($type){
        return \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_user'),'vagueField'=>['lbb_user_id','lbb_user_account','lbb_user_name','lbb_user_mobile','lbb_user_origin', 'created_at'], 'filtField'=>['lbb_user_pwd','lbb_user_paypwd']])->query();
    }
    
    public function modify(){
        
    }
}