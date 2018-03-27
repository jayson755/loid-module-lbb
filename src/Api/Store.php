<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\Store as StoreLogic;
use Loid\Module\Lbb\Logic\Category as CategoryLogic;


class Store extends Controller{
    /**
     * 提现
     */
    public function withdrawing(Request $request){
        try {
            if ((new \Loid\Module\Lbb\Logic\User)->verifyPayPassword($request->user, $request->input('pay_pwd'))) {
                throw new \Exception('支付密码错误');
            }
            (new StoreLogic)->toDoWithdraw($request->user, $request->all());
            
            
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'提现申请成功，敬请等待管理员处理']);
    }
    
    /**
     * 充值
     */
    public function recharge(Request $request){
        try {
            (new StoreLogic)->toDoRecharge($request->user, $request->all());
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'充值申请成功，敬请等待管理员处理']);
    }
}