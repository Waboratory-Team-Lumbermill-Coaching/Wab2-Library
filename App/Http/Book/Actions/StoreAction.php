<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:55 PM
 */

namespace App\Http\Book\Actions;

use App\Http\Book\BookService;
use Base\Actions\CreateAction;

class StoreAction extends CreateAction {
    /**
     * @var BookService
     */
    protected $service;

    protected $rules = [
    ];

    protected $messages = [
    ];

    public function __construct(BookService $service) {
        parent::__construct($service);
    }

    /**
     * @param $data
     * @return \App\Models\User|mixed
     * @throws \Exception
     */
    protected function handleData($data) {
        $book = $this->service->create($data);

        return $book;
    }

}