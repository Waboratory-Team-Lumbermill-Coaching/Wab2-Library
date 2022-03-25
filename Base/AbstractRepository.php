<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 3:49 PM
 */

namespace Base;

use Database\BaseModel;

abstract class AbstractRepository implements RepositoryInterface {
    /**
     * Saves entity
     *
     * @param BaseModel $entity
     * @return bool
     * @throws \Exception
     */
    public function store(BaseModel $entity) {
        $this->getEntityManager()->store($entity);

        return true;
    }

    /**
     * Saves entity
     *
     * @param BaseModel $entity
     * @return bool
     */
    public function update(BaseModel $entity) {
        $this->getEntityManager()->update($entity);

        return true;
    }

    /**
     * Delete entity
     *
     * @param BaseModel $entity
     * @return bool
     */
    public function delete($entity) {
        $this->getEntityManager()->remove($entity);

        return true;
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
            $this->getEntityManager()->flush();
            if($searchArray && is_array($searchArray) && count($searchArray)) {
                return $this->getEntityManager($this->getClassName())
                    ->queryAll($searchArray)
                    ->get();
            }

            return $this->getEntityManager()
                ->getAll($this->getClassName());
        } catch(\Exception $e) {
        }

        return [];
    }

    /**
     * Retrieve entity with its relations by param
     *
     * @param array $params
     * @param string $with
     * @param bool $strict
     * @return BaseModel
     */
    public function findByParam(array $params, $with = '', $strict = false) {
        $data = $this->getEntityManager()->query($params);

        if(!$data) {
            return null;
        }

        $model = $this->getModelInstance();

        foreach($data as $key => $value) {
            $model->{$key} = $value;
        }

        return $model;
    }

    /**
     * findById
     * @param $id
     * @param string $with
     * @return mixed
     */
    public function findById($id, $with = '') {
        return $this->getEntityManager($this->getClassName())
            ->view($this->getModelInstance(), $id);
    }

    /**
     * Get total number of object by array of params
     *
     * @param array $searchArray
     * @param bool $strict
     * @return int
     */
    public function findTotal($searchArray = [], $strict = false) {
        $count = 0;

        return $count;
    }

    public abstract function getClassName();

    /**
     * getRelations
     * @param $relations
     * @return array
     */
    private function getRelations($relations = '') {
        $relations = array_unique(explode(',', $relations));
        $formattedRelations = [];
        foreach($relations as $relation) {
            $value = explode('.', $relation);

            $res = [];
            for($i = count($value) - 1; $i >= 0; $i--) {
                $res = [$value[$i] => $res];
            }
            $formattedRelations = array_merge_recursive($formattedRelations, $res);
        }

        return $formattedRelations;
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager($modelClass = BaseModel::class) {
        $entityManager = EntityManager::getInstance($this->getClassName());

        return $entityManager;
    }

    private function getModelInstance() {
        $model = $this->getClassName();

        return new $model();
    }
}