<?php

namespace Loid\Module\Lbb\Model;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'lbb_category';
    
    public $primaryKey = 'category_id';
    
    public $timestamps = true;
    
    
}
