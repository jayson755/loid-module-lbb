<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\Category as CategoryModel;
use Illuminate\Http\Request;
use Validator;

class Category{
    
    public function add(array $params) :int {
        $validator = Validator::make($params, [
            'category_name' => 'required|unique:lbb_category|max:20',
            'category_url' => 'required',
            'category_status' => 'required|in:on,off',
        ],[
            'category_name.required' => '理财币种必须',
            'category_name.unique' => '理财币种已存在',
            'category_name.max' => '理财币种过长',
            
            'category_url.required' => '币种URL必须',
            
            'category_status.required' => '状态错误',
            'category_status.in' => '状态错误',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        
        $model = new CategoryModel;
        $model->category_name = strtoupper($params['category_name']);
        $model->category_url = $params['category_url'];
        $model->category_status = $params['category_status'];
        $model->save();
        return $model->category_id;
    }
    
    public function modify(array $params) :int {
        $params['category_name'] = strtoupper($params['category_name']);
        $model = (new CategoryModel)->where('category_id', $params['category_id'])->first();
        if (empty($model)) {
            throw new \Exception('修改项不存在');
        }
        if ((new CategoryModel)->where('category_name', $params['category_name'])->where('category_id', '<>', $model->category_id)->count()) {
            throw new \Exception('理财币种已存在');
        }
        $model->category_name = $params['category_name'];
        $model->category_url = $params['category_url'];
        $model->category_status = $params['category_status'];
        $model->save();
        return $model->category_id;
    }
    
    public function getCategoryList(string $type = '', array $field_alias = []){
        if (!$field_alias) {
            $field_alias = ['category_id', 'category_name'];
        }
        if (empty($type)) {
            return CategoryModel::select($field_alias)->orderBy('category_id', 'desc')->get();
        } else {
            return CategoryModel::where('category_status', 'on')->select($field_alias)->orderBy('category_id', 'desc')->get();
        }
    }
    
    public function getCategory(int $category_id){
        return CategoryModel::find($category_id);
    }
}