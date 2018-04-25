<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;
use Loid\Module\Lbb\Model\Article as ArticleModel;
use Loid\Module\Lbb\Controllers\Controller;
use DB;

class ArticleController extends Controller{
    
    public function index(){
        return $this->view("{$this->view_prefix}/article/index", [
            'categoryJson' => json_encode(['notice'=>'公告','aboutus'=>'关于我们'])
        ]);
    }
    
    public function _getList(Request $request, $type){
        return \Loid\Frame\Support\JqGrid::instance(['model'=> DB::table('lbb_article')->whereNull('deleted_at')])->query();
    }
    
    /**
     * 删除
     */
    public function delete(Request $request){
        try {
            $article = ArticleModel::find($request->input('article_id'));
            if (empty($article) || $article->trashed()) {
                throw new \Exception('该内容不存在');
            }
            $article->delete();
            return $this->response(true);
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
    }
    
    public function modify(Request $request){
        if ('GET' == $request->method()) {
            $article = ArticleModel::find($request->input('article_id'));
            return $this->view("{$this->view_prefix}/article/modify", ['article'=>$article]);
        } else {
            try {
                if (!$request->input('article_id')) {
                    $article = new ArticleModel;
                    $article->article_title = $request->input('article_title');
                    $article->article_category = $request->input('article_category');
                    $article->article_content = $request->input('article_content');
                    $article->save();
                } else {
                    $article = ArticleModel::find($request->input('article_id'));
                    $article->article_title = $request->input('article_title');
                    $article->article_category = $request->input('article_category');
                    $article->article_content = $request->input('article_content');
                    $article->save();
                }
            } catch (\Exception $e) {
                return $this->response(false, '', $e->getMessage());
            }
            return $this->response(true);
        }
    }
}