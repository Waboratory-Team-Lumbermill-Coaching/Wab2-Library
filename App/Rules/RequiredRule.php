<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 5:49 PM
 */

namespace App\Rules;

/**
 * @property  $errorMessage
 */
class RequiredRule extends ValidationRule {
    protected $errorMessage = '{{attribute}} is required';

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

        return null;
    }
}