<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:56 PM
 */

namespace App\Http\Genre;

use App\Models\Genre;
use Base\AbstractService;

class GenreService extends AbstractService {
    protected $editableFields = [
        'name',
    ];

    public function create(array $data) {
        $data = $this->sanitize($data);
        $book = new Genre();

        foreach($data as $key => $item) {
            $book->{$key} = $item;
        }

        $this->repository->store($book);

        return $book;
    }

    /**
     * @return Genre[]
     */
    public function index() {
        return $this->repository->findAll();
    }

    public function getById($genre_id) {
        return $this->repository->findById($genre_id);
    }
}