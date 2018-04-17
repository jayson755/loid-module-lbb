<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;
use Loid\Module\Lbb\Model\LbbUser as LbbUserModel;
use Loid\Module\Lbb\Logic\User as UserLogic;
use Loid\Module\Lbb\Controllers\Controller;
use DB;

class UserController extends Controller{
    
    public function index(){
        return $this->view("{$this->view_prefix}/user/index");
    }
    
    public function _getList(Request $request, $type){
        $list = \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_user')->whereNull('deleted_at'),'vagueField'=>['lbb_user_id','lbb_user_account','lbb_user_name','lbb_user_mobile','lbb_user_origin', 'created_at'], 'filtField'=>['lbb_user_pwd','lbb_user_paypwd']])->query();
        
        foreach ($list['rows'] as $key => $val) {
            $list['rows'][$key]['origin'] = DB::table('lbb_user')->where('lbb_user_id', $val['lbb_user_origin'])->value('lbb_user_account');
        }
        return $list;
    }
    
    /**
     * 冻结用户
     */
    public function freeze(Request $request){
        try {
            $LbbUser = LbbUserModel::find($request->input('user_id'));
            if (empty($LbbUser) || $LbbUser->trashed()) {
                throw new \Exception('该用户已被冻结！');
            }
            $LbbUser->delete();
            return $this->response(true);
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
    }
    
    public function modify(Request $request){
        try {
            $LbbUser = LbbUserModel::find($request->input('lbb_user_id'));
            if (empty($LbbUser)) {
                throw new \Exception('用户不存在');
            }
            if ($request->input('lbb_user_pwd')) {
                $LbbUser->lbb_user_pwd = (new UserLogic)->setPassword($request->input('lbb_user_pwd'));
            }
            if ($request->input('lbb_user_paypwd')) {
                $LbbUser->lbb_user_paypwd = (new UserLogic)->setPassword($request->input('lbb_user_paypwd'));
            }
            $LbbUser->lbb_user_mobile = $request->input('lbb_user_mobile');
            $LbbUser->save();
            return $this->response(true);
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
    }
}