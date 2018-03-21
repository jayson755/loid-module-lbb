<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\LbbUser;
use Illuminate\Http\Request;
use Validator;

class User{
    
    /**
     * 添加用户
     * @param array $params 数据
     *
     * @return void
     */
    public function add(array $params) :int {
        $validator = Validator::make($params, [
            'user_account' => 'required|size:11|unique:lbb_user,lbb_user_account',
            'user_mobile' => 'required|size:11',
            'user_pwd' => 'required|min:6|max:20',
            'user_paypwd' => 'required|min:6|max:20',
        ],[
            'user_account.required' => '用户名必须',
            'user_account.size' => '用户名必须为手机号',
            'user_account.unique' => '用户名已存在',
            'user_mobile.required' => '预留手机号必须',
            'user_mobile.size' => '预留手机号错误',
            'user_pwd.required' => '密码必须为6-20位字符',
            'user_pwd.min' => '密码必须为6-20位字符',
            'user_pwd.max' => '密码必须为6-20位字符',
            'user_paypwd.required' => '支付密码必须为6-20位字符',
            'user_paypwd.min' => '支付密码必须为6-20位字符',
            'user_paypwd.max' => '支付密码必须为6-20位字符',
        ]);

        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        
        $model = new LbbUser;
        $model->lbb_user_account = $params['user_account'];
        $model->lbb_user_name = $params['user_name'] ?? '';
        $model->lbb_user_mobile = $params['user_mobile'];
        $model->lbb_user_pwd = $this->setPassword($params['user_pwd']);
        $model->lbb_user_paypwd = $this->setPassword($params['user_paypwd']);
        $model->lbb_user_origin = $params['user_origin'] ?? '';
        $model->save();
        return $model->lbb_user_id;
    }
    
    /**
     * 获取用户信息
     */
    public function getUser(string $account){
        static $_lbb_user;
        if (!isset($_lbb_user[$account])) {
            $_lbb_user[$account] = (new LbbUser)::where('lbb_user_account', $account)->first();
        }
        return $_lbb_user[$account];
    }
    
    /**
     * 用户验证
     */
    public function verify(string $account, string $password) :bool {
        if (empty($account)) throw new \Exception('账号错误');
        if (empty($password)) throw new \Exception('密码错误');
        $user = $this->getUser($account);
        if (empty($user)) {
            throw new \Exception('账号错误');
        }
        return $this->checkPassword($user, $password);
    }
    
    /**
     * 用户支付密码验证
     */
    public function verifyPayPassword(LbbUser $user, string $payPassword) :bool {
        if (empty($payPassword)) return false;
        return $this->checkPayPassword($user, $payPassword);
    }
    
    /**
     * 比对支付密码
     */
    private function checkPayPassword(LbbUser $user, string $payPassword) :bool {
        return (0 === strcmp($this->setPassword($payPassword), $user->lbb_user_paypwd));
    }
    /**
     * 比对密码
     */
    private function checkPassword(LbbUser $user, string $password) :bool {
        return (0 === strcmp($this->setPassword($password), $user->lbb_user_pwd));
    }
    
    /**
     * 字符串生成密码
     */
    private function setPassword(string $str) : string {
        return md5(md5($str) . md5('5bdc5a78e0cc062576ee989ed19b8019'));
    }
}