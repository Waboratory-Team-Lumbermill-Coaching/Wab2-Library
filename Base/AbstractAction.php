<?php

namespace Base;

use App\Helpers\NotificationHelper;
use App\Helpers\ValidatorHelper;

abstract class AbstractAction {
    /**
     * @var AbstractService
     */
    protected $service;

    /**
     * Constructor of AbstractAction.
     *
     * @param AbstractService $service
     */
    public function __construct(AbstractService $service) {
        $this->service = $service;
    }

    protected abstract function handleData($data);

    /**
     * @throws \Exception
     */
    public function __invoke() {
        NotificationHelper::clear();
    }

    /**
     * validateData
     *
     * @return null
     */
    protected function getRequest() {
        return $_POST;
    }

    protected function getParsedData() {
        return array_map(function($param) {
            return htmlspecialchars($param);
        }, $this->getRequest());
    }
}
