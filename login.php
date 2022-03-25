<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 4:46 PM
 */
require_once __DIR__ . '\common.php';
session_start();

use App\Helpers\AppHelper;
use App\Helpers\DrawerHelper;


$user = \App\Helpers\AppHelper::authUser();
if($user) {
    return \App\Helpers\AppHelper::redirect('profile');
}

$formData = [
    'username' => '',
    'password' => '',
];

if(count($_POST)) {
    $formData = $_POST;
    $action = new \App\Http\Users\Actions\LoginAction(
        new \App\Http\User\UserService(
            new \App\Http\User\UserRepository()
        )
    );

    try {
        $response = $action();

        if($response) {
            AppHelper::redirect('/login');
        }
    } catch(Exception $e) {
    }


    $formData['password'] = '';
}

DrawerHelper::renderPage([
    'title' => 'Login',
    'elements' => [
        [
            'tag' => 'form',
            'attributes' => [
                'action' => url('login'),
                'method' => 'POST'
            ],
            'children' => [
                [
                    'tag' => 'p',
                    'children' => [
                        [
                            'tag' => 'label',
                            'value' => 'Username:',
                        ],
                        [
                            'tag' => 'input',
                            'attributes' => [
                                'type' => 'text',
                                'name' => 'username',
                                'value' => $formData['username'],
                            ]
                        ]
                    ],
                ],
                [
                    'tag' => 'p',
                    'children' => [
                        [
                            'tag' => 'label',
                            'value' => 'Password:',
                        ],
                        [
                            'tag' => 'input',
                            'attributes' => [
                                'type' => 'password',
                                'name' => 'password',
                                'value' => $formData['password'],
                            ]
                        ]
                    ],
                ],
                [
                    'tag' => 'button',
                    'value' => 'Login Now!',
                ],
            ]
        ]
    ]
]);