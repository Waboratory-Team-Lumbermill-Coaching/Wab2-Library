<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 4:46 PM
 */
require_once __DIR__ . '\common.php';
session_start();

$user = \App\Helpers\AppHelper::authUser();

if(!$user) {
    return \App\Helpers\AppHelper::redirect('login');
}

$bookService = new \App\Http\User\UserService(
    new \App\Http\User\UserRepository()
);

$books = $bookService->books();

$allowedColumns = [
    'title', 'author', 'genre'
];

$fieldsMapping = [
    'genre' => function($genre) {
        return $genre->name;
    }
];

$books = array_map(function($book) use ($allowedColumns, $fieldsMapping) {
    $result = [];

    foreach($allowedColumns as $column) {
        $columnName = ucfirst($column);

        if(array_key_exists($column, $fieldsMapping)) {
            $result[$columnName] = $fieldsMapping[$column]($book->{$column});

            continue;
        }

        $result[$columnName] = $book->{$column};
    }

    $result['Edit'] = \App\Helpers\AppHelper::renderTag('a', 'Edit book', [
        'href' => url('edit', [
            'id' => $book->id
        ])
    ]);

    $result['Delete'] = \App\Helpers\AppHelper::renderTag('a', 'Delete book', [
        'href' => url('delete', [
            'id' => $book->id
        ])
    ]);

    return $result;

}, $books);


\App\Helpers\DrawerHelper::renderPage([
    'title' => 'My books',
    'menu' => [
        [
            'text' => 'Add new book',
            'url' => url('add_book'),
        ],

        [
            'text' => 'My profile',
            'url' => url('profile'),
        ],

        [
            'text' => 'Logout',
            'url' => url('logout'),
        ]
    ],
    'elements' => [
        [
            'tag' => 'div',
            'value' => \App\Helpers\AppHelper::renderTable($books)
        ]
    ]
]);
