<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\StoreWithdraw;
use Loid\Module\Lbb\Model\StoreRecharge;
use Loid\Module\Lbb\Model\Store as StoreModel;
use Loid\Module\Lbb\Model\Category as CategoryModel;
use Loid\Module\Lbb\Model\LbbUser as LbbUserModel;
use Illuminate\Http\Request;
use Validator;
use DB;

class Store{
    
    /**
     * 获取标签分类
     */
    public function getFlag(){
        return (new StoreModel)->flag;
    }
    
    /**
     * 获取用户所有库存
     */
    public function getStoreByUser(LbbUserModel $user, array $fields = []){
        if ($fields) {
            return StoreModel::where('user_id', $user->lbb_user_id)->select($fields)->get();
        } else {
            return StoreModel::where('user_id', $user->lbb_user_id)->get();
        }
    }
    
    /**
     * 获取用户某一分类的仓库，如果没有则会初始化
     */
    public function getStoreByCategory2User(int $user_id, int $category){
        $store = StoreModel::where('user_id', $user_id)->where('store_category', $category)->first();
        if (empty($store)) {
            $store = $this->initStore($user_id, $category);
        }
        return $store;
    }
    
    /**
     * 充值
     */
    public function toDoRecharge(LbbUserModel $user, array $params) : int{
        $validator = Validator::make($params, [
            'recharge_num' => 'required|numeric|min:0',
            'store_category' => 'required',
        ],[
            'recharge_num.required' => '数量必须',
            'recharge_num.integer' => '数量必须为大于0的数字',
            'recharge_num.min' => '数量必须为大于0的数字',
            
            'store_category.required' => '充值类型必须',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        $category = CategoryModel::where('category_id', $params['store_category'])->where('category_status', 'on')->first();
        if (empty($category)) {
            throw new \Exception('该分类(币种)已下线，不能充值');
        }
        
        $store = $this->getStoreByCategory2User($user->lbb_user_id, (int)$params['store_category']);
        
        $model = new StoreRecharge;
        $model->store_id = $store->store_id;
        $model->user_id = $user->lbb_user_id;
        $model->recharge_url = $category->category_url;
        $model->recharge_num = $params['recharge_num'];
        $model->store_category = (int)$params['store_category'];
        $model->recharge_status = false;
        $model->save();
        return $model->recharge_id;
        
    }
    
    /**
     * 处理充值申请
     */
    public function dealwithRecharge(int $recharge_id){
        try {
            DB::beginTransaction();
            $storeRecharge = StoreRecharge::where('recharge_id', $recharge_id)->where('recharge_status', 0)->first();
            if (empty($storeRecharge)) {
                throw new \Exception('申请不存在或已处理');
            }
            
            $store = $this->getStoreByCategory2User($storeRecharge->user_id, $storeRecharge->store_category);
            
            $store->store_num = bcadd($store->store_num, $storeRecharge->recharge_num, 6);
            
            $store->save();
            $storeRecharge->recharge_status = 1;
            $storeRecharge->save();
            //仓库变动记录
            (new StoreModel)->storeChange($storeRecharge->user_id, $store->store_id, $storeRecharge->store_category, $store->store_num, $storeRecharge->recharge_num, 'in', $storeRecharge->toJson());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * 提现
     */
    public function toDoWithdraw(LbbUserModel $user, array $params) : int{
        $validator = Validator::make($params, [
            'withdraw_url' => 'required|active_url',
            'withdraw_num' => 'required|numeric|min:1',
            'store_category' => 'required',
        ],[
            'withdraw_url.required' => '收款地址必须',
            'withdraw_url.active_url' => '收款地址必须为url',
            
            'withdraw_num.required' => '数量必须',
            'withdraw_num.integer' => '数量必须为大于0的数字',
            'withdraw_num.min' => '数量必须为大于0的数字',
            
            'store_category.required' => '提现类型必须',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        
        if (!CategoryModel::where('category_id', $params['store_category'])->count()) {
            throw new \Exception('该分类(币种)不存在');
        }
        $store = (new StoreModel)->where('user_id', $user->lbb_user_id)->where('store_category', $params['store_category'])->first();
        if (empty($store) || $store->store_num <= 0) {
            throw new \Exception('无库存，不能提现');
        }
        
        $model = new StoreWithdraw;
        $model->store_id = $store->store_id;
        $model->user_id = $user->lbb_user_id;
        $model->withdraw_url = $params['withdraw_url'];
        $model->withdraw_num = $params['withdraw_num'];
        $model->store_category = strtoupper($params['store_category']);
        $model->withdraw_status = 0;
        $model->save();
        return $model->withdraw_id;
    }
    
    
    
    /**
     * 处理提现申请
     */
    public function dealwithWithdraw(int $withdraw_id){
        try {
            DB::beginTransaction();
            $storeWithdraw = StoreWithdraw::where('withdraw_id', $withdraw_id)->where('withdraw_status', 0)->first();
            if (empty($storeWithdraw)) {
                throw new \Exception('申请不存在或已处理');
            }
            $store = $this->getStoreByCategory2User($storeWithdraw->user_id, $storeWithdraw->store_category);
            if ($store->store_num < $storeWithdraw->withdraw_num) {
                throw new \Exception('提现币种数量大于库存币种数量');
            }
            $store->store_num = bcsub($store->store_num, $storeWithdraw->withdraw_num, 6);
            $store->save();
            $storeWithdraw->withdraw_status = 1;
            $storeWithdraw->save();
            
            //仓库变动记录
            (new StoreModel)->storeChange($storeWithdraw->user_id, $store->store_id, $storeWithdraw->store_category, $store->store_num, $storeWithdraw->withdraw_num, 'out', $storeWithdraw->toJson());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    
    /**
     * 初始化仓库
     */
    private function initStore(int $user_id, int $store_category) {
        $store = new StoreModel;
        $store->user_id = $user_id;
        $store->store_category = $store_category;
        $store->store_num = 0;
        $store->save();
        //仓库变动记录
        (new StoreModel)->storeChange($user_id, $store->store_id, $store_category, strval(0), '0');
        return $store;
    }
    
    /**
     * 收支记录
     * @param int $user_id 用户ID
     * @param int $page_index 页码
     * @param string $type 记录类型
     */
    public function record(int $user_id, int $page_index, string $type = ''){
        return (new StoreModel)->record($user_id, $page_index, $type);
    }
    
    /**
     * 推广收益换算
     * @param StoreModel $originStore 推广收益的具体来源库
     * @param string $store_num 推广收益比例的基数
     * @param string $type 推广收益类型
     */
    public function promoteIncome(StoreModel $originStore, string $store_num, string $type){
        $user_id = $originStore->user_id;
        $level = 0;
        foreach (config("business.promote.proportion") as $proportion) {
            $level ++;
            $user_id = LbbUserModel::where('lbb_user_id', $user_id)->value('lbb_user_origin');
            if (empty($user_id)) break;
            $this->userPromoteIncome($user_id, $originStore, $store_num, $level, $type);
        }
    }
    
    /**
     * 推广收入换算实体
     * @param int $user_id 得到推广收益的用户ID
     * @param StoreModel $originStore 收益的具体来源库
     * @param string $store_num 推广收益比例的基数
     * @param int $level 推广收益的层级
     *  @param string $type 推广收益类型
     * 
     */
    private function userPromoteIncome(int $user_id, StoreModel $originStore, string $store_num, int $level, string $type){
        if (!in_array($level, [1, 2, 3])) return;
        if ($store_num <= 0) return;
        
        $store = $this->getStoreByCategory2User($user_id, $originStore->store_category);
        //推广收入
        $promoteIncome = bcmul(bcdiv($store_num, 100, 6), config("business.promote.proportion.level_{$level}"), 6);
        //推广收益入库
        $store->store_num = bcadd($store->store_num, $promoteIncome, 6);
        
        $store->save();
        //仓库变动记录
        (new StoreModel)->storeChange(
            $store->user_id,
            $store->store_id,
            $store->store_category,
            $store->store_num,
            $promoteIncome,
            $type,
            $store->toJson(),
            $originStore->user_id,
            $originStore->store_id
        );
    }
}