<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 4:46 PM
 */

use App\Helpers\AppHelper;
use App\Helpers\DrawerHelper;

session_start();
require_once __DIR__ . '\common.php';

$user = \App\Helpers\AppHelper::authUser();
if($user) {
    return \App\Helpers\AppHelper::redirect('profile');
}

$formData = [
    'username' => '',
    'password' => '',
    'confirm_password' => '',
    'full_name' => '',
    'born_on' => '',
];

if(count($_POST)) {
    $formData = $_POST;

    $createAction = new \App\Http\Users\Actions\CreateAction(
        new \App\Http\User\UserService(
            new \App\Http\User\UserRepository()
        )
    );

    try {
        $response = $createAction();

        if($response) {
            return AppHelper::redirect('/login');
        }

    } catch(Exception $e) {
    }
}

DrawerHelper::renderPage([
    'title' => 'REGISTER NEW USER',
    'elements' => [
        [
            'tag' => 'form',
            'attributes' => [
                'action' => url('register'),
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
                    'tag' => 'div',
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
                    'tag' => 'p',
                    'children' => [
                        [
                            'tag' => 'label',
                            'value' => 'Confirm Password:',
                        ],
                        [
                            'tag' => 'input',
                            'attributes' => [
                                'type' => 'password',
                                'name' => 'confirmPassword',
                                'value' => $formData['confirm_password'],
                            ]
                        ]
                    ],
                ],
                [
                    'tag' => 'p',
                    'children' => [
                        [
                            'tag' => 'label',
                            'value' => 'Full Name:',
                        ],
                        [
                            'tag' => 'input',
                            'attributes' => [
                                'type' => 'text',
                                'name' => 'fullName',
                                'value' => $formData['full_name'],
                            ]
                        ],
                    ],
                ],
                [
                    'tag' => 'p',
                    'children' => [
                        [
                            'tag' => 'label',
                            'value' => 'Born On:',
                        ],
                        [
                            'tag' => 'input',
                            'attributes' => [
                                'type' => 'date',
                                'name' => 'bornOn',
                                'value' => $formData['born_on'],
                            ]
                        ],
                    ],
                ],
                [
                    'tag' => 'button',
                    'value' => 'Register!',
                ],
            ]
        ]
    ]
]);