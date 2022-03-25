<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/23/2022
 * Time: 6:01 PM
 */

namespace App\Models;

use Database\BaseModel;

class Genre extends BaseModel {
    public $fields = [
        'name'
    ];

    public $name;
}