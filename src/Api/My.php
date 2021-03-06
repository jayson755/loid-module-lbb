<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\Store as StoreLogic;
use Loid\Module\Lbb\Logic\Category as CategoryLogic;
use Loid\Module\Lbb\Logic\User as UserLogic;
use Loid\Module\Lbb\Logic\Financial as FinancialLogic;
use Loid\Module\Lbb\Logic\MobileCode as MobileCodeLogic;

class My extends Controller{
    /**
     * 我的理财产品
     */
    public function financial(Request $request){
        try {
            $list = (new FinancialLogic)->getFinancialByUser($request->user->lbb_user_id);
            foreach ($list as $val) {
                $val->date = date('Y.m.d', strtotime($val->effective_date));
            }
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'操作成功', 'data'=>$list->toArray()]);
    }
    
    /**
     * 我的理财产品详情
     */
    public function financialDetial(Request $request, int $id){
        try {
            $userFinancial = (new FinancialLogic)->getFinancialByID($id)->toArray();
            if (empty($userFinancial)) {
                throw new \Exception('不存在');
            }
            $userFinancial['effective_date_show'] = date('Y.m.d', strtotime($userFinancial['effective_date']));
            $userFinancial['closed_date_show'] = date('Y.m.d', strtotime($userFinancial['closed_date']));
            $userFinancial['created_at_show'] = date('Y.m.d', strtotime($userFinancial['created_at']));
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'操作成功', 'data'=>$userFinancial]);
    }
    
    /**
     * 获取我的库存
     */
    public function store(Request $request){
        try {
            $category = (new CategoryLogic)->getCategoryList('on');
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
                        'recharge' => true,
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
     * 我的推广链接
     */
    public function promoteLinks(Request $request){
        try {
            $user = (new UserLogic)->getUser($request->user->lbb_user_account);
            $url = '/api/register.html' . '?origin='. $user->lbb_user_account;
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>['url'=>$url]]);
    }
    
    /**
     * 我的下家
     */
    public function promote(Request $request){
        try {
            $list = (new UserLogic)->getUserPromote($request->user->lbb_user_id);
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>['userlist'=>$list->toArray()]]);
    }
    
    /**
     * 我的收支记录
     */
    public function balancerecord(Request $request, int $category, int $pageindex){
        try {
            $list = (new StoreLogic)->record($request->user->lbb_user_id, $category, $pageindex);
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>['list'=>$list]]);
    }
    
    /**
     * 修改密码
     */
    public function changePassword(Request $request){
        try {
            $code = $request->input('code');
            if (empty($code)) throw new \Exception('验证码错误');
            
            if (true !== (new MobileCodeLogic)->verifyCode($request->user->lbb_user_mobile, 'modify_pwd', $code)) {
                throw new \Exception('验证码错误');
            }
            
            (new UserLogic)->changePassword($request->user->lbb_user_id, (string)$request->input('old'), (string)$request->input('new'), (string)$request->input('confirme'));
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'操作成功']);
    }
    
    /**
     * 修改支付密码
     */
    public function changePayPassword(Request $request){
        try {
            $code = $request->input('code');
            if (empty($code)) throw new \Exception('验证码错误');
            
            if (true !== (new MobileCodeLogic)->verifyCode($request->user->lbb_user_mobile, 'modify_paypwd', $code)) {
                throw new \Exception('验证码错误');
            }
            (new UserLogic)->changePayPassword($request->user->lbb_user_id, (string)$request->input('password'), (string)$request->input('newpaypassword'));
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'操作成功']);
    }
    
}