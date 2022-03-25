<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:57 PM
 */

namespace App\Http\Book;


use App\Models\Book;
use Base\AbstractRepository;
use Base\RepositoryInterface;

class BookRepository extends AbstractRepository implements RepositoryInterface {
    public function getClassName() {
        return Book::class;
    }

    /**
     * Retrieve Entity data
     *
     * @param array $searchArray key/value array
     * @param string $orderBy orderBy param
     * @param integer $pageSize limit param
     * @param array $page offset param
     * @param string $with
     * @param bool $strict
     *
     * @return array array of Entity Object
     */
    public function findAll($searchArray = [], $orderBy = 'id', $pageSize = null, $page = null, $with = '', $strict = false) {
        try {
           
        } catch(\Exception $e) {
        }

        return [];
    }

}