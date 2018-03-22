<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;

class UserFinancial extends Model
{
    protected $table = 'lbb_user_financial';
    
    public $primaryKey = 'id';
    
    public $timestamps = true;
    
}
