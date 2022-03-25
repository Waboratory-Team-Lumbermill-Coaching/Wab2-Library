<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 5:49 PM
 */

namespace App\Rules;

use Database\DBManager;

/**
 * @property  $errorMessage
 */
class UniqueRule extends ValidationRule {
    protected $errorMessage = '{{attribute}} is already taken.';

    /**
     * @return ValidationRule|null|string
     * @throws \Exception
     */
    public function handle() {
        if(!$this->formData || !count($this->formData)) {
            return $this->getErrorMessage();
        }

        if(!array_key_exists($this->input, $this->formData)) {
            return $this->getErrorMessage();
        }

        if(!$this->formData[$this->input]) {
            return $this->getErrorMessage();
        }

        if($this->isExists()) {
            return $this->getErrorMessage();
        }

        return null;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    private function isExists() {
        $dbManager = DBManager::getInstance();

        return !!$dbManager->useTable($this->config[0])
            ->where([
                [$this->config[1], '=', $this->inputValue]
            ])
            ->first();
    }
}