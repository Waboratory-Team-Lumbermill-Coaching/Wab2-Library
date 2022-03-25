<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/22/2022
 * Time: 4:43 PM
 */

namespace App\Rules;


class SameRule extends ValidationRule {
    protected $errorMessage = '{{attribute}} must be the equal as {{other}} field';
    private $otherFieldValue = null;

    public function __construct($input, $config = null, $customMessage = null) {
        parent::__construct($input, $config, $customMessage);

        $this->otherFieldValue = $this->formData[$this->config[0]];
    }

    /**
     * @return ValidationRule
     */
    public function handle() {
        if($this->inputValue === $this->otherFieldValue) {
            return null;
        }

        return $this->getErrorMessage();
    }

    protected function getErrorMessageParserConfig() {
        return [
            'other' => ucfirst(str_replace('_', ' ', $this->config[0]))
        ];
    }
}