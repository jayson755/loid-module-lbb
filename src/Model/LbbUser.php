<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;

class LbbUser extends Model
{
    protected $table = 'lbb_user';
    
    public $primaryKey = 'lbb_user_id';
    
    public $timestamps = true;
    
    
}
