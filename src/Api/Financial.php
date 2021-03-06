<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\Financial as FinancialLogic;
use Loid\Module\Lbb\Model\BusinessSet as BusinessSetModel;

class Financial extends Controller{
    
    /**
     * 获取理财产品
     */
    public function getlist(Request $request){
        try {
            $financial_limit = (new BusinessSetModel)->getBusiness('financial_limit');
            $limit = array_column($financial_limit, 'date', 'index');
            $list = (new FinancialLogic)->getFinancial();
            foreach ($list as $val) {
                $val->category = $val->category;
            }
            $financialList = [];
            foreach ($list->toArray() as $val) {
                $temp = [
                    'financial_id' => $val['financial_id'],
                    'category_name' => $val['category']['category_name'],
                ];
                $financialList[$limit[$val['financial_limit']]][] = $temp;
            }
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>$financialList]);
    }
    
    /**
     * 购买理财产品
     */
    public function buy(Request $request){
        try {
            (new FinancialLogic)->buyFinancial($request->user, $request->all());
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'操作成功']);
    }
}