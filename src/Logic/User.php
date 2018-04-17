<?php
namespace Loid\Module\Lbb\Logic;

use Loid\Module\Lbb\Model\LbbUser as LbbUserModel;
use Illuminate\Http\Request;
use Ramsey\Uuid\Uuid;
use Validator;

class User{
    
    /**
     * 获取用户树形
     */
    public function getUserPromote(int $userId){
        return LbbUserModel::where('lbb_user_origin', $userId)->select('lbb_user_id as id','lbb_user_account as name')->get();
    }
    
    /**
     * 获取用户树形
     */
    public function getUserTree(int $userId){
        $list = LbbUserModel::where('lbb_user_origin', '>', 0)->select('lbb_user_id as id','lbb_user_origin as origin','lbb_user_account as name')->get()->toArray();
        return (new \Loid\Frame\Support\Tree($list, ['id', 'origin']))->leaf($userId);
    }
    
    /**
     * 添加用户
     * @param array $params 数据
     *
     * @return void
     */
    public function add(array $params) :int {
        $validator = Validator::make($params, [
            'user_account' => 'required|min:6|max:20|unique:lbb_user,lbb_user_account',
            'user_mobile' => 'required|size:11',
            'user_pwd' => 'required|min:6|max:20',
            'user_paypwd' => 'required|min:6|max:20',
        ],[
            'user_account.required' => '用户名必须',
            'user_account.min' => '用户名必须为6-20个字符',
            'user_account.max' => '用户名必须为6-20个字符',
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
        if (base64_decode($params['user_origin'] ?? '')) {
            $origin_user_id = LbbUserModel::where('lbb_user_uuid', base64_decode($params['user_origin']))->value('lbb_user_id');
        }
        $model = new LbbUserModel;
        $model->lbb_user_account = $params['user_account'];
        $model->lbb_user_name = $params['user_name'] ?? '';
        $model->lbb_user_mobile = $params['user_mobile'];
        $model->lbb_user_pwd = $this->setPassword($params['user_pwd']);
        $model->lbb_user_paypwd = $this->setPassword($params['user_paypwd']);
        $model->lbb_user_origin = $origin_user_id ?? 0;
        $model->lbb_user_uuid = Uuid::uuid1()->toString();
        $model->save();
        return $model->lbb_user_id;
    }
    
    /**
     * 修改密码
     */
    public function changePassword(int $user_id, string $old, string $new, string $valide){
        $validator = Validator::make(['old'=>$old, 'password'=> $new, 'password_confirmation'=> $valide], [
            'old' => 'required',
            'password' => 'required|min:6|max:20|confirmed',
            'password_confirmation' => 'required',
        ],[
            'old.required' => '原密码必须',
            'password.required' => '密码必须为6-20个字符',
            'password.min' => '密码必须为6-20个字符',
            'password.max' => '密码必须为6-20个字符',
            'password.confirmed' => '确认密码不一致',
            'password_confirmation.required' => '确认密码必须',
        ]);
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        $user = LbbUserModel::where('lbb_user_id', $user_id)->first();
        if (false === $this->checkPassword($user, $old)) {
            throw new \Exception('原密码错误');
        }
        $user->lbb_user_pwd = $this->setPassword($new);
        $user->save();
    }
    
    /**
     * 修改支付密码
     */
    public function changePayPassword(int $user_id, string $password, string $newpaypassword){
        $validator = Validator::make(['password'=> $password, 'newpaypassword'=> $newpaypassword], [
            'password' => 'required',
            'newpaypassword' => 'required|min:6|max:20',
        ],[
            'password.required' => '密码错误',
            'newpaypassword.required' => '支付密码必须为6-20个字符',
            'newpaypassword.min' => '支付密码必须为6-20个字符',
            'newpaypassword.max' => '支付密码必须为6-20个字符',
        ]);
        if ($validator->fails()) {
            throw new \Exception($validator->errors()->first());
        }
        $user = LbbUserModel::where('lbb_user_id', $user_id)->first();
        if (false === $this->checkPassword($user, $password)) {
            throw new \Exception('密码错误');
        }
        $user->lbb_user_paypwd = $this->setPassword($newpaypassword);
        $user->save();
    }
    
    /**
     * 获取用户信息
     */
    public function getUser(string $account){
        static $_lbb_user;
        if (!isset($_lbb_user[$account])) {
            $_lbb_user[$account] = (new LbbUserModel)::where('lbb_user_account', $account)->first();
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
    public function verifyPayPassword(LbbUserModel $user, string $payPassword) :bool {
        if (empty($payPassword)) return false;
        return $this->checkPayPassword($user, $payPassword);
    }
    
    /**
     * 比对支付密码
     */
    private function checkPayPassword(LbbUserModel $user, string $payPassword) :bool {
        return (0 === strcmp($this->setPassword($payPassword), $user->lbb_user_paypwd));
    }
    /**
     * 比对密码
     */
    private function checkPassword(LbbUserModel $user, string $password) :bool {
        return (0 === strcmp($this->setPassword($password), $user->lbb_user_pwd));
    }
    
    /**
     * 字符串生成密码
     */
    public function setPassword(string $str) : string {
        return md5(md5($str) . md5('5bdc5a78e0cc062576ee989ed19b8019'));
    }
}