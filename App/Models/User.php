<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/23/2022
 * Time: 5:32 PM
 */

namespace App\Models;

use Database\BaseModel;

class User extends BaseModel {
    public $fields = [
        'username',
        'password',
        'full_name',
        'born_on',
    ];

    public $username;
    public $password;
    public $full_name;
    public $born_on;
}