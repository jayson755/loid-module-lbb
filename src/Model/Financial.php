<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;

class Financial extends Model
{
    protected $table = 'lbb_financial';
    
    public $primaryKey = 'financial_id';
    
    public $timestamps = true;
    
    public function category(){
        return $this->hasOne('Loid\Module\Lbb\Model\Category', 'category_id', 'financial_category')->select('category_name');
    }
}
