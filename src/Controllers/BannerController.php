<?php

namespace Loid\Module\Lbb\Controllers;

use Illuminate\Http\Request;
use Loid\Module\Lbb\Model\Banner as BannerModel;
use Loid\Module\Lbb\Controllers\Controller;

class BannerController extends Controller{
    
    public function set(){
        return $this->view("{$this->view_prefix}/banner/set", ['list'=>BannerModel::get()]);
    }
    
    public function setSave(Request $request){
        try {
            if (!$request->has('banner')) {
                foreach (BannerModel::get() as $val) {
                    $val->delete();
                }
            } else {
                $hasId = [];
                foreach ($request->input('banner') as $key => $val) {
                    $hasId[] = $key;
                    $banner = BannerModel::find($key);
                    if (empty($banner)) continue;
                    $banner->banner_link = $val ?? '';
                    $banner->save();
                }
                if ($hasId) {
                    BannerModel::whereNotIn('banner_id', $hasId)->delete();
                }
            }
            if ($request->has('url')) {
                foreach ($request->input('url') as $key => $val) {
                    $banner = new BannerModel;
                    $banner->banner_url = $val;
                    $banner->banner_link = $request->input('link')[$key] ?? '';
                    $banner->save();
                }
            }
        } catch (\Exception $e) {
            return $this->response(false, '', $e->getMessage());
        }
        return $this->response(true);
    }
    
}