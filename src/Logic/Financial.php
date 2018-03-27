<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\Financial as FinancialModel;
use Loid\Module\Lbb\Model\UserFinancial as UserFinancialModel;
use Loid\Module\Lbb\Model\Store as StoreModel;
use Illuminate\Http\Request;
use Loid\Module\Lbb\Model\LbbUser;
use Validator;
use DB;

class Financial{
    
    public function add(array $params) :int {
        $validator = Validator::make($params, [
            'financial_category' => 'required|integer|min:1',
            'financial_limit' => 'required|integer|min:1',
            'financial_status' => 'required|in:on,off',
        ],[
            'financial_category.required' => '理财币种必须',
            'financial_category.integer' => '理财币种必须为正整数',
            'financial_category.min' => '理财币种必须为正整数',
            
            'financial_limit.required' => '理财期限必须',
            'financial_limit.integer' => '理财期限必须为正整数',
            'financial_limit.min' => '理财期限必须为正整数',
            
            'category_status.required' => '状态错误',
            'category_status.in' => '状态错误',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        if (FinancialModel::where('financial_category', $params['financial_category'])->where('financial_limit', $params['financial_limit'])->count()) {
            throw new \Exception('该币种该天数已存在');
        }
        
        $model = new FinancialModel;
        $model->financial_category = $params['financial_category'];
        $model->financial_limit = $params['financial_limit'];
        $model->financial_status = $params['financial_status'];
        $model->save();
        return $model->financial_id;
    }
    
    public function modify(array $params) :int {
        $params['financial_id'] = intval($params['financial_id']);
        $model = (new FinancialModel)->where('financial_id', $params['financial_id'])->first();
        if (empty($model)) {
            throw new \Exception('修改项不存在');
        }
        if (FinancialModel::where('financial_id', '<>' , $params['financial_id'])->where('financial_category', $params['financial_category'])->where('financial_limit', $params['financial_limit'])->count()) {
            throw new \Exception('该币种该天数已存在');
        }
        $model->financial_category = $params['financial_category'];
        $model->financial_limit = $params['financial_limit'];
        $model->financial_status = $params['financial_status'];
        $model->save();
        return $model->financial_id;
    }
    
    /**
     * 获取理财产品
     */
    public function getFinancial(){
        return FinancialModel::where('financial_status', 'on')->select('financial_id','financial_category','financial_limit')->get();
    }
    
    /**
     * 获取用户理财产品
     */
    public function getFinancialByUser(int $user_id){
        return UserFinancialModel::where('user_id', $user_id)->select('id','category_name','limit_date','financial_status','effective_date')->get();
    }
    
    /**
     * 购买理财产品
     */
    public function buyFinancial(LbbUser $user, array $params) :void {
        if (empty($params['financial_id'])) {
            throw new \Exception('理财产品不存在或已下架');
        }
        $financial = FinancialModel::where('financial_id', $params['financial_id'])->where('financial_status', 'on')->first();
        if (empty($financial)) {
            throw new \Exception('理财产品不存在或已下架');
        }
        if (doubleval($params['num']) <= 0) {
            throw new \Exception('购买数量必须大于0');
        }
        //查看仓库余额是否充足
        $userStore = StoreModel::where('user_id', $user->lbb_user_id)->where('store_category', $financial->financial_category)->first();
        if (empty($userStore) || $userStore->store_num < (double)$params['num']) {
            throw new \Exception('余额不足，请先充值');
        }
        try {
            DB::beginTransaction();
            $business = config('business.financial_limit')[$financial->financial_limit];
            $model = new UserFinancialModel;
            $model->financial_id = $financial->financial_id;
            $model->financial_category = $financial->financial_category;
            $model->category_name = $financial->category->category_name;
            $model->num = $params['num'];
            $model->limit_date = $business['date'];
            $model->user_id = $user->lbb_user_id;
            $model->financial_status = 'on';
            $model->effective_date = date('Y-m-d H:i:s');
            $model->closed_date = date('Y-m-d H:i:s', time() + $business['date'] * 86400);
            $model->financial_num = bcmul($params['num'], $business['rate'], 6);
            $model->save();
            
            //减库存
            $userStore->store_num = bcsub($userStore->store_num, (double)$params['num'], 6);
            $userStore->save();
            
            //仓库变动记录
            (new StoreModel)->storeChange($user->lbb_user_id, $userStore->store_id, $financial->financial_category, $userStore->store_num, $params['num'], 'buy', $model->toJson());
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}