<?php

return $config['origin'] = array(
    'list' => array(
        'http://127.0.0.1:3000',
        'http://192.168.0.3:3000',
        'http://crm.nfcpass.cn',
        'http://localhost:8081',
        'http://tree.nfcpass.cn',
        'https://tree.nfcpass.cn',
    ),
    'access' => array(
        'methods' => 'HEAD, GET, POST, DELETE, PUT, OPTIONS',
        'credentials' => true,
        'headers' => 'Content-Type, Content-Range, Content-Disposition, Content-Description, User-Token',
    )
);


?>