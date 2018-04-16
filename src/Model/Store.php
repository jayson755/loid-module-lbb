<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Store extends Model
{
    protected $table = 'lbb_store';
    
    public $primaryKey = 'store_id';
    
    public $timestamps = true;
    
    public $rows = 20;
    
    public $flag = [
        'in' => '充值',
        'out' => '提现',
        'interest' => '余额利息',
        'expire' => '理财利润',
        'buy' => '购买理财',
        'balance_promote' => '余额利息推广收益',
        'financial_promote' => '理财利润推广收益',
        'event' => '活动赠送'
    ];
    
    public function category(){
        return $this->hasOne('Loid\Module\Lbb\Model\Category', 'category_id', 'store_category')->select('category_name');
    }
    
    /**
     * 仓库变动记录
     * @param int $user_id 用户ID
     * @param int $store_id 仓库ID
     * @param int $store_category 分类ID
     * @param string $last_num 剩余数量
     * @param string $change_num 变动数量
     * @param string $flag 变动类型
     * @param string $json 引起变动对象json串
     * @param int $origin_user 引起变动来源用户 常用于推广收益来源
     * @param int $origin_store 引起变动来源仓库 常用于推广收益来源
     */
    public function storeChange(int $user_id, int $store_id, int $store_category, string $last_num, string $change_num, string $flag = '', string $json = '', int $origin_user = 0, int $origin_store = 0){
        //仓库变动计息记录
        DB::table('lbb_store_change')->insert([
            'user_id' => $user_id,
            'store_id' => $store_id,
            'store_category' => $store_category,
            'last_num' => $last_num,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if ($json) {
            //仓库变动记录
            DB::table('lbb_store_log')->insert([
                'user_id' => $user_id,
                'store_id' => $store_id,
                'store_category' => $store_category,
                'flag' => $flag,
                'store_num' => $change_num,
                'last_num' => $last_num,
                'store_data' => $json,
                'origin_user_id' => $origin_user,
                'origin_store_id' => $origin_store,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
    
    /**
     * 收支记录
     */
    public function record(int $user_id, int $category, int $page_index, string $type = ''){
        $object = DB::table('lbb_store_log')->select('log_id','store_category','flag','store_num','last_num','created_at')->where('user_id', $user_id)->where('store_category', $category);
        if (in_array($type, $this->flag)) {
            $object = $object->where('flag', $type);
        }
        $page = ($page_index >= 1) ? $page_index : 1;
        return $object->offset(($page - 1) * $this->rows)->limit($this->rows)->get();
    }
}
