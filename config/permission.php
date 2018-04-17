<?php

return [
    /*不用登陆就能访问的方法*/
    'no_login_method' => [],
    
    /*不用授权就能访问的类*/
    'no_auth_class' => [],
    
    /*不用授权就能访问的方法*/
    'no_auth_method' => ['getjQGridList'],
    
    /*不用授权就能访问的类方法*/
    'no_auth_class_method' => [],
    
    /*菜单权限配置*/
    
    'menus' => [
        'lbb' => [
            'label' => 'Lbb管理',
            'icon'  => 'fa-file',
            'menu'  => array(
                array('label' => '业务设置','display'=>true, 'alias' => 'lbb.business.set', 'method' => 'get'),
                
                array('label' => '用户管理','display'=>true, 'alias' => 'lbb.user', 'method' => 'get'),
                
                array('label' => '仓库管理','display'=>true, 'alias' => 'lbb.store', 'method' => 'get'),
                
                array('label' => '仓库日志记录','display'=>true, 'alias' => 'lbb.store.log', 'method' => 'get'),
                
                array('label' => '提现管理','display'=>true, 'alias' => 'lbb.store.withdrawing', 'method' => 'get'),
                
                array('label' => '充值管理','display'=>true, 'alias' => 'lbb.store.recharge', 'method' => 'get'),
                
                array('label' => '币种分类管理','display'=>true, 'alias' => 'lbb.category', 'method' => 'get'),
                
                array('label' => '理财产品管理','display'=>true, 'alias' => 'lbb.financial', 'method' => 'get'),
                
                array('label' => '用户理财管理','display'=>true, 'alias' => 'lbb.user.financial', 'method' => 'get'),
            ),
            
        ],
    ],
];