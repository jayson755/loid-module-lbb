<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\MobileCode as MobileCodeModel;
use Log;


class MobileCode{

    private $user;
    
    private $key;
    
    private $url;
    
    
    public function __construct(){
        $this->url = config('business.mobile_code.url');
        $this->user = config('business.mobile_code.user');
        $this->key = config('business.mobile_code.key');
    }

    public function getCode(){
        return strval(mt_rand(1000, 9999));
    }
    
    /**
     * 验证短信验证码
     */
    public function verifyCode(string $mobile, string $type, string $code){
        if (MobileCodeModel::where('mobile_code_mobile', $mobile)->where('mobile_code_type', $type)->where('mobile_code_str', $code)->where('created_at', '>', date("Y-m-d H:i:s", strtotime('-1 day')))->count()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 发送验证码
     * @param $mobile 手机号
     * @param $type 使用用途
     * @param $code 验证码
     */
    public function sendCode(string $mobile, string $type, string $code){
        try {
            if (MobileCodeModel::where('mobile_code_mobile', $mobile)->where('created_at', '>', date("Y-m-d H:i:s", time() - 60))->count()) {
                throw new \Exception('请勿在60秒之内连续发送短信！');
            }
            
            //发送短信的内容,签名前置:【网云】验证码：8888
            $content = config("business.code_type.{$type}") . "验证码：{$code}";
            $post = 'user=' . $this->user . '&key=' . $this->key . '&mobile=' . $mobile . '&content=' . $content;
            
            $this->curl_request($post);
            
            $mobileCodeModel = new MobileCodeModel;
            $mobileCodeModel->mobile_code_mobile = $mobile;
            $mobileCodeModel->mobile_code_type = $type;
            $mobileCodeModel->mobile_code_str = $code;
            $mobileCodeModel->save();
            return true;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
    
    private function curl_request($post = ''){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $this->url);
        //curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); 
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); //有请求的返回值
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); //重定向时
        curl_setopt($curl, CURLOPT_NOSIGNAL, 1); //以毫秒为超时计时单位一定要设置这个 
        curl_setopt($curl, CURLOPT_TIMEOUT_MS, 20000); //2秒超时PHP 5.2.3起可使用 
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
        $data = curl_exec($curl);
        Log::emergency("短信：" . json_encode($data));
        if (curl_errno($curl)) {
            Log::error("短信错误：" . json_encode(curl_error($curl)));
            throw new \Exception('短信发送错误：' . curl_error($curl));
        }
        curl_close($curl);
    }
    
}
