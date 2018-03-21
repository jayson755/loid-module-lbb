<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;

class StoreRecharge extends Model
{
    protected $table = 'lbb_store_recharge';
    
    public $primaryKey = 'recharge_id';
    
    public $timestamps = true;
    
    
}
