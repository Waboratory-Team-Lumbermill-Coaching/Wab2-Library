<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/23/2022
 * Time: 5:25 PM
 */

namespace Database;

class BaseModel {
    public $id;

    protected $tableName = null;
    private $pluralIes = [
    ];

    public function getTableName() {
        if($this->tableName) {
            return $this->tableName;
        }

        $className = get_class($this);
        $classItems = explode('\\', $className);
        $model = array_pop($classItems);

        $this->tableName = strtolower($model) . 's';

        return $this->tableName;
    }
}