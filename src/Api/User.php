<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\User as UserLogic;

class User extends Controller{
    
    /**
     * 注册
     */
    public function register(Request $request){
        try {
            (new UserLogic)->add($request->all());
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'注册成功']);
    }
    
    /**
     * 登录
     */
    public function signin(Request $request){
        try {
            $userLogic = new UserLogic;
            if (true !== $userLogic->verify($request->input('user_account'), $request->input('user_pwd'))) {
                throw new \Exception('账号或密码错误');
            }
            $user = $userLogic->getUser($request->input('user_account'));
            $request->session()->put('lbb_user', $user);
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'验证成功']);
    }
    
    /**
     * 用户的推广用户
     */
    public function promote(Request $request, int $user_id){
        try {
            $list = (new UserLogic)->getUserPromote($user_id);
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>['userlist'=>$list->toArray()]]);
    }
}