<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/19/2022
 * Time: 5:07 PM
 */

use App\Helpers\DrawerHelper;

session_start();
require_once __DIR__.'\common.php';

DrawerHelper::renderPage([
    'title' => 'Hello, Guest',
    'elements' => [
        [
            'tag' => 'p',
            'value' => [
                'template' => 'If you have an account, you can {{login}} to our system. Otherwise, just {{register}}.',
                'template_parse' => [
                    'login' =>  [
                        'elements' => [
                            'a' => [
                                'value' => 'login',
                                'attributes' => [
                                    'href' => url('login'),
                                ]
                            ],
                        ]
                    ],

                    'register' =>  [
                        'elements' => [
                            'a' => [
                                'value' => 'register',
                                'attributes' => [
                                    'href' => url('register'),
                                ]
                            ]
                        ]
                    ]
                ]
            ],
        ],
    ]
]);

//
//
//file_put_contents("result.txt", "");
//$data = \Helpers\AppHelper::renderTable([
//    [
//        'title' => '',
//        'genre' => '',
//    ],
//    [
//        'title' => '',
//        'genre' => '',
//        'sth' => '',
//    ],
//    [
//        'title' => '',
//        'genre' => '',
//    ],
//    [
//        'title' => '',
//        'genre' => '',
//    ],
//    [
//        'title' => '',
//        'genre' => '',
//    ],
//]);
//
//file_put_contents("result.txt", $data);
//
//echo \Helpers\NotificationHelper::success('this is my success notification');
//echo \Helpers\NotificationHelper::error('this is my error notification');
//
//\Helpers\NotificationHelper::getNotification();
//
//dd(\Helpers\NotificationHelper::hasNotification() ? 'y' : 'n');
