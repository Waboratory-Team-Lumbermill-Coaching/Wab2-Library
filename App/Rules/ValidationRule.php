<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 6:18 PM
 */

namespace App\Rules;

use App\Helpers\AppHelper;

abstract class ValidationRule {
    protected $input;
    protected $formData = [];
    protected $config = null;
    protected $inputValue = null;
    protected $errorMessage = '{{attribute}} is invalid';
    protected $customMessage = null;

    public function __construct($input, $config = null, $customMessage = null) {
        $this->input = $input;
        $this->formData = $_POST;
        $this->config = $this->parseConfig($config);
        $this->inputValue = $this->formData[$this->input];
        $this->customMessage = $customMessage;
    }

    /**
     * @return ValidationRule
     */
    public abstract function handle();

    protected function getErrorMessageParserConfig() {
        return null;
    }

    protected function getErrorMessage() {
        return $this->parseErrorMessagePlaceholders();
    }

    private function parseConfig($config) {
        if(!$config) {
            return null;
        }

        return explode(',', $config);
    }

    private function parseErrorMessagePlaceholders() {
        $message = $this->getMessage();

        $parserConfig = $this->getErrorMessageParserConfig();
        if(!$parserConfig) {
            return ucfirst(AppHelper::parseTemplate(
                'attribute',
                ucwords(str_replace('_', ' ', $this->input)),
                $message
            ));
        }

        $template = AppHelper::parseTemplate('attribute', ucwords(str_replace('_', ' ', $this->input)), $message);

        foreach($parserConfig as $placeholder => $value) {
            $template = AppHelper::parseTemplate($placeholder, $value, $template);
        }

        return ucfirst($template);
    }

    private function getMessage() {
        return $this->customMessage ?: $this->errorMessage;
    }
}