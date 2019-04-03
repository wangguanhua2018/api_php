<?php

//返回所有的api请求规则
return 
[
    [
        'class' => 'yii\rest\UrlRule', 
        'controller' => 'site',
        'extraPatterns' => [
            'POST login' => 'login',
            'GET sites' => 'index',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule', 
        'controller' => ['test/default'],
        'extraPatterns' => [
            'GET defaults' => 'index',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule', 
        'controller' => 'admin',
        'extraPatterns' => [
            'POST,OPTIONS login' => 'login'
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule', 
        'controller' => 'rbac',
        'extraPatterns' => [
            'GET,OPTIONS permissions' => 'permission',
            'POST,OPTIONS permissions' => 'create',
            'POST,OPTIONS update' => 'edit',
            'GET,OPTIONS roles' => 'test',
            'POST,OPTIONS users' => 'createuser',
            'POST,OPTIONS updateuser' => 'updateuser',
            'GET,OPTIONS users' => 'user',
            'GET,OPTIONS nodes' => 'nodes',
            'POST,OPTIONS access' => 'access',
            'DELETE permissions' => 'del',
            'DELETE users' => 'deluser',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule', 
        'controller' => 'article',
        'extraPatterns' => [
            'POST,OPTIONS upload' => 'upload',
            'POST,OPTIONS update' => 'edit',
            'DELETE,OPTIONS delete' => 'del',
        ],
    ],
    [
        'class' => 'yii\rest\UrlRule',
        'controller' => 'tag',
        'extraPatterns' => [
            'POST,OPTIONS update' => 'edit',
            'DELETE,OPTIONS delete' => 'del',
        ],
    ],
];