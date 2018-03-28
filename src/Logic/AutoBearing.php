<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\UserFinancial as UserFinancialModel;
use Loid\Module\Lbb\Model\Store as StoreModel;
use Loid\Module\Lbb\Logic\Store as StoreLogic;
use DB;
use Log;

class AutoBearing{
    
    
    public static function execute(){
        //这里有个小逻辑，先处理余额计息，再处理定存宝计息，因为余额三天后计息，若有变动则重置时间
        $object = new self;
        $object->balance();
        $object->financial();
    }
    
    /**
     * 处理余额计息,按三天内最低额计息
     */
    public function balance(){
        $time = date('Y-m-d H:i:s', strtotime('-3 day'));
        DB::table('lbb_store_change')->where('created_at', '<', $time)->delete();
        foreach (StoreModel::where('user_id', 4)->get() as $val) {
            try {
                DB::beginTransaction();
                //获取该分类最近三天的最低金额
                $minNum = DB::table('lbb_store_change')
                    ->where('created_at', '>', $time)
                    ->where('user_id', $val->user_id)
                    ->where('store_category', $val->store_category)
                    ->min('last_num');
                $minNum = $minNum ?? $val->store_num;
                if ($minNum > 0) {
                    //利息
                    $interest = bcmul($minNum, config('business.balance_rate') ,6);
                    $json = $val->toJson();
                    $val->store_num = bcadd($val->store_num, $interest, 6);
                    $val->save();
                    //仓库变动记录
                    (new StoreModel)->storeChange($val->user_id, $val->store_id, $val->store_category, $val->store_num, $interest, 'interest', $json);
                    //3级上级收益入库
                    (new StoreLogic)->promoteIncome($val, $interest, 'balance_promote');
                }
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::emergency('余额计息失败：store_id='. $val->store_id . '；原因：' . $e->getMessage());
            }
        }
    }
    
    /**
     * 处理定存宝到期计息
     */
    private function financial(){
        while($userFinancial = UserFinancialModel::where('financial_status', 'on')->first()){
            try {
                DB::beginTransaction();
                $userFinancial->financial_status = 'off';
                $userFinancial->save();
                //定存宝本金+收益
                $num = bcadd($userFinancial->num, $userFinancial->financial_num, 6);
                $userStore = (new StoreLogic)->getStoreByCategory2User($userFinancial->user_id, $userFinancial->financial_category);
                $userStore->store_num = bcadd($userStore->store_num, $num, 6);
                $userStore->save();
                
                //仓库变动记录
                (new StoreModel)->storeChange($userFinancial->user_id, $userStore->store_id, $userFinancial->financial_category, $userStore->store_num, bcadd($userFinancial->num, $userFinancial->financial_num, 6), 'expire', $userFinancial->toJson());
                //3级上级收益入库
                (new StoreLogic)->promoteIncome($userStore, $userFinancial->financial_num, 'financial_promote');
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Log::emergency('定存宝到期计息失败：id='. $userFinancial->id . '；原因：' . $e->getMessage());
            }
        }
    }
}