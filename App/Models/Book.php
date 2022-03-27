<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/23/2022
 * Time: 5:32 PM
 */

namespace App\Models;

use Database\BaseModel;

class Book extends BaseModel {
    public $fields = [
        'title',
        'author',
        'description',
        'image',
        'genre_id',
        'user_id',
    ];

    public $title;
    public $author;
    public $description;
    public $image;
    public $genre_id;
    public $user_id;
}