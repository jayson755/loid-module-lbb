<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'lbb_store';
    
    public $primaryKey = 'store_id';
    
    public $timestamps = true;
    
    public function category(){
        return $this->hasOne('Loid\Module\Lbb\Model\Category', 'category_id', 'store_category')->select('category_name');
    }
}
