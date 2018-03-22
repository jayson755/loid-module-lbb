<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Store extends Model
{
    protected $table = 'lbb_store';
    
    public $primaryKey = 'store_id';
    
    public $timestamps = true;
    
    public function category(){
        return $this->hasOne('Loid\Module\Lbb\Model\Category', 'category_id', 'store_category')->select('category_name');
    }
    
    /**
     * 仓库变动记录
     */
    public function storeChange(int $user_id, int $store_category, $last_num, $change_num = '', string $flag = '', string $json = ''){
        //仓库变动计息记录
        DB::table('lbb_store_change')->insert([
            'user_id' => $user_id,
            'store_category' => $store_category,
            'last_num' => $last_num,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        if ($json) {
            //仓库变动记录
            DB::table('lbb_store_log')->insert([
                'user_id' => $user_id,
                'store_category' => $store_category,
                'flag' => $flag,
                'store_num' => $change_num,
                'last_num' => $last_num,
                'store_data' => $json,
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
