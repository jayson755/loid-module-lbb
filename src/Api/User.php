<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\User as UserLogic;
use Loid\Module\Lbb\Logic\MobileCode as MobileCodeLogic;

class User extends Controller{
    
    /**
     * 注册
     */
    public function register(Request $request){
        try {
            
            $mobile = $request->input('user_mobile');
            if (11 != strlen($mobile)) throw new \Exception('预留手机号错误');
            
            $code = $request->input('code');
            if (empty($code)) throw new \Exception('验证码错误');
            
            if (true !== (new MobileCodeLogic)->verifyCode($mobile, 'signin', $code)) {
                throw new \Exception('验证码错误');
            }
            
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
            $token = $user->lbb_user_uuid;
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'验证成功', 'token'=>$token]);
    }
    
    /**
     * 登出
     */
    public function logout(Request $request){
        $request->session()->forget('lbb_user');
        return response()->json(['status'=>1,'msg'=>'已登出']);
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