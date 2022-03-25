<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:56 PM
 */

namespace App\Http\User;


use App\Helpers\AppHelper;
use App\Models\User;
use Base\AbstractRepository;
use Base\AbstractService;

class UserService extends AbstractService {
    /**
     * @var \App\Http\Book\BookService
     */
    public $bookService;
    protected $editableFields = [
        'username',
        'password',
        'full_name',
        'born_on',
    ];

    public function __construct(AbstractRepository $repository) {
        parent::__construct($repository);

        $this->bookService = new \App\Http\Book\BookService(
            new \App\Http\Book\BookRepository(),
            new \App\Http\Genre\GenreService(
                new \App\Http\Genre\GenreRepository()
            )
        );
    }

    public function create(array $data) {
        $data = $this->sanitize($data);
        $user = new User();

        foreach($data as $key => $item) {
            $user->{$key} = $item;
        }

        $user->username = strtolower($user->username);
        $user = $this->hashPassword($user);

        $this->repository->store($user);

        return $user;
    }

    /**
     * @param $params ['username' => '', 'password' => '']
     * @return User
     */
    public function authenticate($params) {
        $user = $this->repository->findByParam([
            'username' => $params['username']
        ]);

        if(!$user) {
            return null;
        }

        if(password_verify($params['password'], $user->password)) {
            return $user;
        }

        return null;
    }

    public function books() {
        $this->bookService->index([
            'user_id' => AppHelper::authUser()->id
        ]);
        dd(65);
    }

    private function hashPassword($user) {
        $user->password = password_hash($user->password, PASSWORD_DEFAULT);

        return $user;
    }
}