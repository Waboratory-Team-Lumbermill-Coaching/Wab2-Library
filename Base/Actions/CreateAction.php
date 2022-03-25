<?php

namespace Base\Actions;

use App\Helpers\NotificationHelper;
use App\Helpers\ValidatorHelper;
use Base\AbstractAction;
use Base\AbstractService;

abstract class CreateAction extends AbstractAction {
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @return mixed
     * @throws \Exception
     */
    public function __invoke() {
        parent::__invoke();

        $request = $this->getRequest();
        $validatorError = $this->validateData($request);

        if($validatorError) {
            NotificationHelper::error($validatorError);

            return null;
        }

        $params = $this->getParsedData();

        return $this->handleData($params);
    }

    /**
     * validateData
     *
     * @return null
     */
    protected function validateData($data) {
        if(!count($this->rules)) {
            return null;
        }

        if(count($this->messages)) {
            return ValidatorHelper::make($this->rules, $this->messages);
        }

        return ValidatorHelper::make($this->rules);
    }
}
