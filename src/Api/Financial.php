<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\Financial as FinancialLogic;

class Financial extends Controller{
    
    /**
     * 获取理财产品
     */
    public function getlist(Request $request){
        try {
            $limit = config('business.financial_limit');
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
}