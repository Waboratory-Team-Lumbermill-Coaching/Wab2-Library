<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:57 PM
 */

namespace App\Http\User;


use App\Models\User;
use Base\AbstractRepository;
use Base\RepositoryInterface;

class UserRepository extends AbstractRepository implements RepositoryInterface {
    public function getClassName() {
        return User::class;
    }
}