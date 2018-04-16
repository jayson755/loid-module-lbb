<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreWithdraw extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $table = 'lbb_store_withdraw';
    
    public $primaryKey = 'withdraw_id';
    
    public $timestamps = true;
    
    
}
