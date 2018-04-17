<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class BusinessSet extends Model
{
    protected $table = 'lbb_business_set';
    
    public $primaryKey = 'param_id';
    
    public $timestamps = true;
    
    private $business;
    
    public function getBusiness(string $flag = ''){
        if (!isset($this->business)) $this->setBusiness();
        return empty($flag) ? $this->business : $this->business[$flag];
    }
    
    private function setBusiness(){
        if (!$this->count()) $this->Seeder();
        
        foreach ($this->get()->toArray() as $val) {
            $this->business[$val['param_name']] =  json_decode($val['param_value'], true);
        }
    }
    
    /**
     * 这里放弃使用数据填充
     */
    public function Seeder(){
        DB::statement('TRUNCATE `'.config('database.connections.mysql.prefix').'lbb_business_set`');
        $business = [
            //定存宝利息
            'financial_limit' => [
                '1' => ['index' => 1, 'date' => 30, 'rate' => 0.004],
                '2' => ['index' => 2, 'date' => 60, 'rate' => 0.006],
                '3' => [ 'index' => 3, 'date' => 90, 'rate' => 0.008],
            ],
            'balance_rate' => 0.002, //余额利息
            /*推广收益*/
            'promote' => [
                //是否开启
                'enable' => true,
                //比例
                'proportion' => ['level_1' => 15,'level_2' => 10,'level_3' => 5
                ]
            ]
        ];
        foreach ($business as $key => $val) {
            $param_type = is_array($val) ? 'array' : 'string';
            $sql = "INSERT INTO `".config('database.connections.mysql.prefix')."lbb_business_set` (`param_name`,`param_value`,`param_type`) VALUES ('{$key}','".json_encode($val)."','{$param_type}');";
            DB::insert($sql);
        }
    }
}
