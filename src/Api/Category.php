<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\Category as CategoryLogic;

class Category extends Controller{
    
    /**
     * 获取业务分类
     */
    public function getlist(Request $request){
        try {
            $list = (new CategoryLogic)->getCategory($request->input('type', ''));
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>$list->toArray()]);
    }
}