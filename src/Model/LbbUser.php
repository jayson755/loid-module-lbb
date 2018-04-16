<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LbbUser extends Model
{
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];
    
    protected $table = 'lbb_user';
    
    public $primaryKey = 'lbb_user_id';
    
    public $timestamps = true;
    
    
}
