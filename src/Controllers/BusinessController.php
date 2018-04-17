<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;
use Loid\Module\Lbb\Model\BusinessSet as BusinessSetModel;
use Loid\Module\Lbb\Controllers\Controller;


class BusinessController extends Controller{
    
    public function set(){
        $business = ((new BusinessSetModel)->getBusiness());
        return $this->view("{$this->view_prefix}/business/set", ['business'=>$business]);
    }
    
    public function setSave(Request $request){
        try {
            foreach ($request->all() as $key => $val) {
                $set = BusinessSetModel::where('param_name', $key)->first();
                $param_value = json_decode($set->param_value, true);
                
                if ($key == 'balance_rate') {
                    if (doubleval($val) <= 0) throw new \Exception('数值不正确，请核查');
                    $val = bcadd(doubleval($val), 0, 6);
                    $param_value = $val;
                    
                } elseif ($key == 'financial_limit') {
                    $rate_1 = bcadd(doubleval($val[1]['rate']), 0, 6);
                    if ($rate_1 <= 0) throw new \Exception('数值不正确，请核查');
                    $param_value['1']['rate'] = $rate_1;
                    
                    $rate_2 = bcadd(doubleval($val[2]['rate']), 0, 6);
                    if ($rate_2 <= 0) throw new \Exception('数值不正确，请核查');
                    $param_value['2']['rate'] = $rate_2;
                    
                    $rate_3 = bcadd(doubleval($val[3]['rate']), 0, 6);
                    if ($rate_3 <= 0) throw new \Exception('数值不正确，请核查');
                    $param_value['3']['rate'] = $rate_3;
                } elseif ($key == 'promote') {
                    $param_value = json_decode($set->param_value, true);
                    
                    $rate_1 = bcadd(doubleval($val['proportion']['level_1']), 0, 6);
                    if ($rate_1 <= 0) throw new \Exception('数值不正确，请核查');
                    $param_value['proportion']['level_1'] = $rate_1;
                    
                    $rate_2 = bcadd(doubleval($val['proportion']['level_2']), 0, 6);
                    if ($rate_2 <= 0) throw new \Exception('数值不正确，请核查');
                    $param_value['proportion']['level_2'] = $rate_2;
                    
                    $rate_3 = bcadd(doubleval($val['proportion']['level_3']), 0, 6);
                    if ($rate_3 <= 0) throw new \Exception('数值不正确，请核查');
                    $param_value['proportion']['level_3'] = $rate_3;
                }
                $set->param_value = json_encode($param_value);
                $set->save();
            }
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
        return $this->response(true);
    }
    
}