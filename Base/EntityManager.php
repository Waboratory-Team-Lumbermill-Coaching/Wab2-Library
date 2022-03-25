<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 4:07 PM
 */

namespace Base;


use Database\BaseModel;
use Database\DBManager;

class EntityManager {
    private static $instance;

    private function __construct() {
    }

    /**
     * @return EntityManager
     */
    public static function getInstance($modelClass = BaseModel::class) {
        if(!self::$instance) {
            self::$instance = new EntityManager();
            self::$instance->getDbManager($modelClass);
        }

        return self::$instance;
    }

    /**
     * @param BaseModel $entity
     * @return bool
     * @throws \Exception
     */
    public function store(BaseModel $entity) {
        return $this->getDbManager()->insert($entity);
    }

    /**
     * @param $className
     * @return BaseModel[]
     * @throws \Exception
     */
    public function getAll($className) {
        $results = $this->getDbManager()
            ->all($className)
            ->get();

        $this->getDbManager()->flush();

        return $results;
    }

    public function queryAll($params) {
        $query = [];

        foreach($params as $key => $value) {
            $query[] = [
                $key, '=', $value
            ];
        }

        return $this->getDbManager()
            ->where($query);
    }

    public function query($params) {
        $query = [];

        foreach($params as $key => $value) {
            $query[] = [
                $key, '=', $value
            ];
        }

        return $this->getDbManager()
            ->where($query)
            ->first();
    }

    public function view(BaseModel $model, $id) {
        return $this->getDbManager($model)
            ->getById($id)
            ->first();
    }

    public function flush() {
        $this->getDbManager()->flush();
    }

    /**
     * @param string $modelClass
     * @return DBManager
     */
    private function getDbManager($modelClass = BaseModel::class) {
        return DBManager::getInstance($modelClass);
    }
}