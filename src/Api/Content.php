<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Model\Article as ArticleModel;
use Loid\Module\Lbb\Model\Banner as BannerModel;

class Content extends Controller{
    
    /**
     * 获取公告
     */
    public function notice(Request $request){
        try {
            $content = ArticleModel::where('article_category', 'notice')->orderBy('article_id', 'desc')->first();
            if ($content) {
                
            }
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>$content]);
    }
    
    /**
     * 获取关于我们
     */
    public function aboutus(Request $request){
        try {
            $content = ArticleModel::where('article_category', 'aboutus')->orderBy('article_id', 'desc')->first();
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>$content]);
    }
    /**
     * 获取关于我们
     */
    public function banner(Request $request){
        try {
            $banner = BannerModel::orderBy('banner_id', 'desc')->get();
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'', 'data'=>$banner]);
    }
}