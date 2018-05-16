<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\UserFinancial as UserFinancialModel;
use Loid\Module\Lbb\Model\Store as StoreModel;
use Loid\Module\Lbb\Logic\Store as StoreLogic;
use Loid\Module\Lbb\Model\BusinessSet as BusinessSetModel;
use DB;
use Log;

class AutoRedemption{
    
    public static function execute(){
        $object = new self;
        $object->financial();
    }
    
    /**
     * 处理定存宝到期计息
     */
    private function financial(){
        while($userFinancial = UserFinancialModel::where('closed_date', '<', date('Y-m-d H:i:s'))->where('financial_status', 'on')->first()){
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
                Log::error('定存宝到期计息失败：id='. $userFinancial->id . '；原因：' . $e->getMessage());
            }
        }
    }
    
}