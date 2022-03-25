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

$formData = [
    'title' => '',
    'author' => '',
    'description' => '',
    'image_url' => '',
    'genre' => '',
];

if(count($_POST)) {
    $formData = $_POST;

    $storeAction = new \App\Http\Book\Actions\StoreAction(
        new \App\Http\Book\BookService(
            new \App\Http\Book\BookRepository(),
            new \App\Http\Genre\GenreService(
                new \App\Http\Genre\GenreRepository()
            )
        )
    );

    try {
        $response = $storeAction();

        if($response) {
//            return AppHelper::redirect('/profile');
        }
    } catch(Exception $e) {
    }

}

$createAction = new App\Http\Book\Actions\CreateAction(
    new App\Http\Book\BookService(
        new \App\Http\Book\BookRepository(),
        new \App\Http\Genre\GenreService(
            new \App\Http\Genre\GenreRepository()
        )
    )
);

try {
    $createAction();
    } catch(Exception $e) {
}
