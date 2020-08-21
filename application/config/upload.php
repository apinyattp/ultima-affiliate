<?php
//$config[ENDPOINT] = RULE

// $config[ENDPOINT] = [
//     'ext' => 'jpg|jpeg|png',
//     'size' => 2 * 1024, //(KB)
//     'auth' => [
//         'role' => [
//             'ROLE_NAME'
//         ],
//         'role' => [ // WITH SUBROLE
//             'ROLE_NAME' => ['SUBROLE_NAME']
//         ],
//     ]
// ];

$config['campaign'] = [
    'ext' => 'jpg|jpeg|png',
    'size' => 1 * 1024, //(MB)
    'auth' => [
        // 'role' => [
        //     'admin' => TRUE
        // ]
    ]
];
