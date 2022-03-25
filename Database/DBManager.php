<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/23/2022
 * Time: 4:53 PM
 */

namespace Database;

use App\Helpers\AppHelper;

class DBManager {
    /**
     * @var DBManager
     */
    private static $instance = null;
    public $query = null;
    /**
     * @var DBConnection
     */
    private $connection = null;
    /**
     * @var BaseModel
     */
    private $modelInstance = null;

    private function __construct() {
    }

    public static function getInstance($model = BaseModel::class) {
        if(!self::$instance) {
            self::$instance = new DBManager();
            self::$instance->connection = DBConnection::getInstance();
            self::$instance->modelInstance = $model;
        }

        return self::$instance;
    }

    private static function createModelInstance($modelClass) {
        $manager = self::getInstance();

        $manager->modelInstance = new $modelClass();
    }

    public function where($query) {
        $manager = self::getInstance();

        $q = '';

        foreach($query as $item) {
            $q .= $item[0]
                . $item[1]
                . "'" . $manager->connection->escapeSpecialChars($item[2]) . "'";
        }
        $manager->query['WHERE'] = $q;

        return $manager;
    }

    /**
     * @param $modelClass
     * @param array $searchArray
     * @return DBManager
     * @throws \Exception
     */
    public function all($modelClass) {
        $manager = self::getInstance();

        self::createModelInstance($modelClass);

        $manager->query = [
            'SELECT' => '*',
            'FROM' => '`' . $this->getTableName() . '`',
        ];

        return $manager;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function get() {
        $manager = self::getInstance();

        if(!array_key_exists('SELECT', $manager->query)) {

            $manager->query = array_merge(
                [
                    'SELECT' => '*',
                    'FROM' => '`' . $this->getTableName() . '`',
                ],
                $manager->query
            );
        }

        $qResults = $manager->connection->query($manager->query);

        if(!$qResults || $qResults->num_rows === 0) {
            return [];
        }

        $results = [];
        $class = get_class($this->modelInstance);
        while($row = $qResults->fetch_assoc()) {
            $entity = new $class();

            foreach($row as $key => $value) {
                $entity->{$key} = $value;
            }

            $results[] = $entity;
        }

        return $results;
    }

    /**
     * @param BaseModel $model
     * @return bool
     * @throws \Exception
     */
    public function insert(BaseModel $model) {
        $this->getManager()->modelInstance = $model;

        $modelFields = get_object_vars($model);

        $fields = array_keys($modelFields);

        if(array_key_exists('fields', $modelFields)) {
            $fields = $modelFields['fields'];
        }

        $filteredFields = array_map(function($field) {
            return '`' . $field . '`';
        }, $fields);

        $values = [];

        foreach($fields as $field) {
            $values[] = $modelFields[$field];
        }

        $filteredValues = [];

        foreach($values as $value) {
            $value = $this->getManager()->connection->escapeSpecialChars($value);
            $filteredValues[] = "'" . $value . "'";
        }

        $this->query = [
            'INSERT INTO' => '`' . $this->getTableName() . '` (' . implode(', ', $filteredFields) . ')',
            'VALUES' => '(' . implode(', ', $filteredValues) . ')'
        ];

        $response = $this->getManager()
            ->connection
            ->query(
                $this->getManager()->query
            );

        if(isset($response->error)) {
            return false;
        }

        return !!$this->getManager()
            ->connection
            ->query(
                $this->getManager()->query
            );
    }

    public function flush() {
        self::$instance = null;
    }

    /**
     * @param $tableName
     * @return DBManager
     * @throws \Exception
     */
    public function useTable($tableName) {
        if(!$this->connection->hasTable($tableName)) {
            throw new \Exception('Table "' . $tableName . '" does not exists in the DB');
        }

        $this->query = [
            'SELECT' => '*',
            'FROM' => AppHelper::parseTemplate('tableName', $tableName, '`{{tableName}}`'),
        ];

        return $this;
    }

    public function first() {
        if(!array_key_exists('SELECT', $this->query)) {
            return null;
        }

        if(!array_key_exists('WHERE', $this->query)) {
            $this->query['WHERE'] = '1=1';
        }

        $this->query['LIMIT'] = '1';

        $modelObj = (object)$this->connection->query($this->query)->fetch_assoc();

        if($this->modelInstance && $this->modelInstance !== BaseModel::class) {
            $data = (object)$this->connection->query($this->query)->fetch_assoc();
            foreach($data as $key => $value) {
                $this->modelInstance->{$key} = $value;
            }

            return $this->modelInstance;
        }

        if(!$this->connection->query($this->query)->num_rows) {
            return null;
        }


        return $modelObj;
    }

    public function getById($id) {
        $this->query = [
            'SELECT' => '*',
            'FROM' => AppHelper::parseTemplate('tableName', $this->getTableName(), '`{{tableName}}`'),
            'WHERE' => '`id`=' . $id
        ];

        return $this;
    }

    /**
     * @return null|string
     * @throws \Exception
     */
    private function getTableName() {
        $manager = self::getInstance();

        $tableName = $manager->modelInstance;
        print_r($tableName);
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        echo '<br>';
        if(is_string($tableName)) {
            $manager->modelInstance = new $tableName();
        }

        $tableName = $manager->modelInstance->getTableName();

        if(!$manager->connection->hasTable($tableName)) {
            throw new \Exception('Table "' . $tableName . '" does not exists in the DB');
        }

        return $tableName;
    }

    /**
     * @param string $model
     * @return DBManager
     */
    private function getManager($model = BaseModel::class) {
        return self::getInstance($model);
    }

    private function setQuery($array) {
        $this->query = $array;
    }
}