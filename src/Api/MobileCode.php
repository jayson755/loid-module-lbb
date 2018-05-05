<?php

namespace Loid\Module\Lbb\Api;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Loid\Module\Lbb\Logic\MobileCode as MobileCodeLogic;

class MobileCode extends Controller{
    
    
    public function getCode(Request $request){
        try {
            $type = $request->input('type');
            $mobile = $request->input('mobile');
            
            if (11 != strlen($mobile)) throw new \Exception('手机号不正确');
            
            $codeType = '';
            if ($type == 'signin') {
                $codeType = 'signin';
            } elseif ($type == 'modifypwd') {
                $codeType = 'modify_pwd';
            } elseif ($type == 'modifypaypwd') {
                $codeType = 'modify_paypwd';
            } else {
                throw new \Exception('不存在该类型');
            }
            $mobileCodeLogic = new MobileCodeLogic;
            $mobileCodeLogic->sendCode($mobile, $codeType, $mobileCodeLogic->getCode());
        } catch (\Exception $e) {
            return response()->json(['status'=>0,'msg'=>$e->getMessage()]);
        }
        return response()->json(['status'=>1,'msg'=>'已发送短信']);
        
    }
}