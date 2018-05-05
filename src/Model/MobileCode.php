<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;

class MobileCode extends Model{
    
    
    protected $table = 'lbb_mobile_code';
    
    public $primaryKey = 'mobile_code_id';
    
    public $timestamps = true;
    
    
}
