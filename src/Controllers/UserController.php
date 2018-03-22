<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;

use Loid\Module\Lbb\Controllers\Controller;
use DB;

class UserController extends Controller{
    
    public function index(){
        return $this->view("{$this->view_prefix}/user/index");
    }
    
    public function _getList(Request $request, $type){
        return \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_user'),'vagueField'=>['lbb_user_id','lbb_user_account','lbb_user_name','lbb_user_mobile','lbb_user_origin', 'created_at'], 'filtField'=>['lbb_user_pwd','lbb_user_paypwd']])->query();
    }
    
    public function modify(){
        
    }
}