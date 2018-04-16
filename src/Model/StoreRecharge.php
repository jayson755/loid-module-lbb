<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreRecharge extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $table = 'lbb_store_recharge';
    
    public $primaryKey = 'recharge_id';
    
    public $timestamps = true;
    
    
}
