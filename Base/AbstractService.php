<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 3/2/2022
 * Time: 3:27 PM
 */

namespace Base;


class AbstractService {
    protected $repository;

    protected $notFoundCode;

    protected $notFoundMessage;

    protected $foreignKeyConstraintCode;

    protected $foreignKeyConstraintMessage;

    protected $editableFields = [];

    /**
     * AbstractService constructor
     *
     * @param AbstractRepository $repository
     */
    public function __construct(AbstractRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * Clear System Params From Search Query
     *
     * @param array $queryParams array
     * @return array $queryParams
     */
    protected static function clearSystemParamsFromSearchQuery(array $queryParams) {
        unset($queryParams['page']);
        unset($queryParams['pageSize']);
        unset($queryParams['orderBy']);
        unset($queryParams['with']);
        unset($queryParams['strict']);

        return $queryParams;
    }

    /**
     * create
     *
     * @param array $data array of params from the request
     * @return mixed object instance
     * @throws \Exception
     */
    public function create(array $data) {
        $data = $this->sanitize($data);
        $entityClass = $this->repository->getClassName();
        $entity = new $entityClass();

        foreach($data as $key => $item) {
            $entity->{$key} = $item;
        }


        $this->repository->store($entity);

        return $entity;
    }

    /**
     * read
     *
     * @param array $queryParams
     * @return array array of object
     */
    public function read(array $queryParams = []) {
        $queryParams = $this->sanitize($queryParams);
        $page = array_key_exists('page', $queryParams) ? $queryParams['page'] : null;
        $pageSize = array_key_exists('pageSize', $queryParams) ? $queryParams['pageSize'] : null;
        $orderBy = array_key_exists('orderBy', $queryParams) ? $queryParams['orderBy'] : 'id';
        $with = array_key_exists('with', $queryParams) ? $queryParams['with'] : '';
        $strict = isset($queryParams['strict']) ? (bool)$queryParams['strict'] : false;

        $queryParams = self::clearSystemParamsFromSearchQuery($queryParams);

        return $this->repository->findAll($queryParams, $orderBy, $pageSize, $page, $with, $strict);
    }

    /**
     * Retrieve object by id
     *
     * @param integer $id id of entity
     * @param string $with
     * @return mixed
     * @throws \Exception
     */
    public function readById($id, $with = '') {
        $entity = $this->repository->findById($id, $with);

        if(!$entity) {
            throw new NotFoundException(sprintf($this->notFoundMessage, $id), $this->notFoundCode);
        }

        return $entity;
    }

    /**
     * Retrieve object by some params
     *
     * @param array $params
     * @param string $with
     * @param bool $strict
     * @return \Database\BaseModel
     */
    public function readOne(array $params, $with = '', $strict = false) {
        $params = $this->sanitize($params);

        $entity = $this->repository->findByParam($params, $with, $strict);

        return $entity;
    }

    /**
     * update
     *
     * @param integer $id id of entity
     * @param array $data array of params from the request
     * @param $overwrite
     * @return mixed object of domain entity instance
     * @throws \Exception
     */
    public function update($id, array $data, $overwrite) {
        $data = $this->sanitize($data);

        $entity = $this->repository->find($id);

        if(!$entity) {
            throw new \Exception();
//            throw new NotFoundException(sprintf($this->notFoundMessage, $id), $this->notFoundCode);
        }

        $entity->setData($data['data'], $overwrite);
        $this->repository->store($entity);

        return $entity;
    }

    /**
     * delete
     *
     * @param integer $id id of entity
     * @return mixed deleted object
     * @throws \Exception
     */
    public function delete($id) {
        $entity = $this->repository->find($id);

        if(!$entity) {
            throw new NotFoundException(sprintf($this->notFoundMessage, $id), $this->notFoundCode);
        }

        try {
            return $this->repository->delete($entity);
        } catch(ForeignKeyConstraintViolationException $exception) {
            throw new \Exception(sprintf($this->foreignKeyConstraintMessage, $id), $this->foreignKeyConstraintCode);
        }
    }

    /**
     * Retrieve total number of object
     *
     * @param $queryParams
     * @return int total number
     */
    public function getTotalNum($queryParams = []) {
        $queryParams = $this->sanitize($queryParams);

        $strict = isset($queryParams['strict']) ? (bool)$queryParams['strict'] : false;
        $queryParams = self::clearSystemParamsFromSearchQuery($queryParams);

        return (int)$this->repository->findTotal($queryParams, $strict);
    }

    /**
     * sanitize - Strip whitespace from the beginning and end of a string
     *
     * @param array $params
     * @return array
     */
    public function sanitize(array $params) {
        $trimmedParams = [];

        foreach($params as $key => $param) {
            if(in_array($key, $this->editableFields)) {
                $trimmedParams[$key] = $param;
            }
        }

        return $trimmedParams;
    }
}