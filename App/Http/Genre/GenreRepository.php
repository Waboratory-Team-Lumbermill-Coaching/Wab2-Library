<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:57 PM
 */

namespace App\Http\Genre;


use App\Models\Genre;
use Base\AbstractRepository;
use Base\RepositoryInterface;

class GenreRepository extends AbstractRepository implements RepositoryInterface {
    public function getClassName() {
        return Genre::class;
    }
}