<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;

class StoreWithdraw extends Model
{
    protected $table = 'lbb_store_withdraw';
    
    public $primaryKey = 'withdraw_id';
    
    public $timestamps = true;
    
    
}
