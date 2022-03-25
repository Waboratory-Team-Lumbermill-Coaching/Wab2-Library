<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/6/2022
 * Time: 5:45 PM
 */

require_once __DIR__ . '\common.php';
session_start();

use App\Helpers\DrawerHelper;

$user = \App\Helpers\AppHelper::authUser();
if(!$user) {
    return \App\Helpers\AppHelper::redirect('login');
}

DrawerHelper::renderPage([
    'title' => \App\Helpers\AppHelper::parseTemplate(
        'name',
        $user->full_name,
        'Hello, {{name}}'
    ),
    'menu' => [
        [
            'text' => 'Add new book',
            'url' => url('add_book')
        ],
        [
            'text' => 'Logout',
            'url' => url('logout')
        ]
    ],
    'elements' => [
        [
            'tag' => 'div',
            'attributes' => [],
            'children' => [
                [
                    'tag' => 'p',
                    'children' => [
                        [
                            'tag' => 'a',
                            'attributes' => [
                                'href' => url('my_books')
                            ],
                            'value' => 'My books'
                        ]
                    ]
                ],
                [
                    'tag' => 'p',
                    'children' => [
                        [
                            'tag' => 'a',
                            'attributes' => [
                                'href' => url('all_books')
                            ],
                            'value' => 'All books'
                        ]
                    ]
                ]
            ]
        ],
    ]
]);