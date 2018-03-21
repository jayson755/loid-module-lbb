<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\Financial as FinancialModel;
use Illuminate\Http\Request;
use Validator;

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
}