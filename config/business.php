<?php
return [
    //定存宝利息
    'financial_limit' => [
        '1' => [
            'index' => 1,
            'date' => 30,
            'rate' => 0.004
        ],
        '2' => [
            'index' => 2,
            'date' => 60,
            'rate' => 0.006
        ],
        '3' => [
            'index' => 3,
            'date' => 90,
            'rate' => 0.008
        ],
    ],
    
    'balance_rate' => 0.002, //余额利息
    
    /*推广收益*/
    'promote' => [
        //是否开启
        'enable' => true,
        //比例
        'proportion' => [
            'level_1' => 15,
            'level_2' => 10,
            'level_3' => 5
        ]
    ],
];