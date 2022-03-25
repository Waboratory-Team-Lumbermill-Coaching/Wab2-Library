<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 3:44 PM
 */

namespace Base;


use Database\BaseModel;

interface RepositoryInterface {
    /**
     * Saves model entity.
     *
     * @param BaseModel $model Entity to save
     */
    public function store(BaseModel $model);

    /**
     * Delete entity.
     *
     * @param BaseModel $model Entity to delete
     */
    public function delete(BaseModel $model);

    /**
     * Retrieve data
     * @return array array of Entity Objects
     * @internal param array $searchArray key/value array
     * @internal param string $orderBy orderBy param
     * @internal param int $pageSize limit param
     * @internal param array $page offset param
     */
    public function findAll();

    /**
     * Retrieve data by array of params
     *
     * @param array $searchArray
     * @return array Entity Object
     */
    public function findByParam(array $searchArray);

    /**
     * Get total number of object by array of params
     *
     * @param array $searchArray
     * @return integer
     */
    public function findTotal($searchArray);
}