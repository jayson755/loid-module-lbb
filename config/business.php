<?php
return [
    //短信验证码配置
    'mobile_code' => [
        'url' => 'http://api.smschn.cn',
        'user' => URLEncode('lianbaobao'),
        'key' => 'E647C3AEED715234942B8B4323ED321E',
    ],
    'code_type' => [
        'signin' => '注册',
        'modify_pwd' => '修改密码',
        'modify_paypwd' => '修改支付密码',
    ]
];