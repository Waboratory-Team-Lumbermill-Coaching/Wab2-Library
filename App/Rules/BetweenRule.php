<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 5:49 PM
 */

namespace App\Rules;

use App\Helpers\AppHelper;

class BetweenRule extends ValidationRule {
    private $errorMessageStr = '{{attribute}} must be between {{min}} and {{max}} characters!';
    private $errorMessageNum = '{{attribute}} must be between {{min}} and {{max}}!';
    private $isInteger = false;

    public function __construct($input, $config = null, $customMessage = null) {
        parent::__construct($input, $config, $customMessage);

//        $this->isInteger = is_numeric($this->inputValue);
        $this->errorMessage = $this->isInteger ? $this->errorMessageNum : $this->errorMessageStr;
    }

    public function handle() {
//        if($this->isInteger) {
//            return $this->handleIntegerValue();
//        }

        return $this->handleNaNValue();
    }

    public function getErrorMessageParserConfig() {
        return [
            'min' => $this->config[0],
            'max' => $this->config[1],
        ];
    }

    protected function buildErrorMessage() {
        $template = AppHelper::parseTemplate(
            'attribute',
            $this->input,
            $this->errorMessage
        );
        $template = AppHelper::parseTemplate('min', $this->config[0], $template);
        $template = AppHelper::parseTemplate('max', $this->config[1], $template);

        return ucfirst($template);
    }

    private function handleIntegerValue() {
        return $this->validate($this->inputValue);
    }

    private function handleNaNValue() {
        return $this->validate(strlen($this->inputValue));
    }

    private function validate($num) {
        if($num < $this->config[0]) {
            return $this->getErrorMessage();
        }

        if($num > $this->config[1]) {
            return $this->getErrorMessage();
        }

        return null;
    }
}