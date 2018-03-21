<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\Store as StoreLogic;
use Loid\Module\Lbb\Logic\Category as CategoryLogic;


class Store extends Controller{
    
    /**
     * 获取用户库存
     */
    public function getStoreByUser(Request $request){
        try {
            $category = (new CategoryLogic)->getCategory('on');
            $list = (new StoreLogic)->getStoreByUser($request->user, ['store_category', 'store_num']);
            foreach ($list as $val) {
                $val->category = $val->category;
            }
            $store = $list->toArray();
            foreach ($store as &$item) {
                $item['recharge'] = false;
            }
            foreach ($category as $val) {
                $ishave = false;
                foreach ($store as  $k => $v) {
                    if ($val->category_id == $v['store_category']) {
                        $store[$k]['recharge'] = true;
                        $ishave = true;
                        break;
                    }
                }
                if (false === $ishave) {
                    $store[] = [
                        'recharge' => false,
                        'store_category' => $val->category_id,
                        'store_num' => 0,
                        'category' => [
                            'category_name' => $val->category_name
                        ]
                    ];
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>$store]);
    }
    
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