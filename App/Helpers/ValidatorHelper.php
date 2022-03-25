<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 5:34 PM
 */

namespace App\Helpers;

use App\Rules\BetweenRule;
use App\Rules\ExistsRule;
use App\Rules\RequiredRule;
use App\Rules\SameRule;
use App\Rules\UniqueRule;

class ValidatorHelper {
    private static $rules = [
        'required' => RequiredRule::class,
        'between' => BetweenRule::class,
        'same' => SameRule::class,
        'unique' => UniqueRule::class,
        'exists' => ExistsRule::class,
    ];

    public static function make($rules, $messages = null) {
        foreach($rules as $input => $rule) {
            $error = self::validateInput($input, $rule, $messages);

            if($error) {
                return $error;
            }
        }

        return null;
    }

    private static function validateInput($input, $ruleString, $messages) {
        $rules = explode('|', $ruleString);

        foreach($rules as $rule) {
            $validationRule = self::getValidationRule($rule, $input, $messages);
            $errorMessage = $validationRule->handle();

            if(!$errorMessage) {
                continue;
            }

            if($errorMessage) {
                return $errorMessage;
            }
        }

        return null;
    }

    private static function getValidationRule($rule, $input, $messages) {
        $validationEntities = explode(':', $rule);

        if(count($validationEntities) > 1) {
            return new self::$rules[$validationEntities[0]](
                $input, $validationEntities[1], self::getCustomErrorMessage($input, $validationEntities[0], $messages)
            );
        }

        return new self::$rules[$rule]($input, null, self::getCustomErrorMessage($input, $validationEntities[0], $messages));
    }

    private static function getCustomErrorMessage($input, $rule, $messages) {
        if(!$messages) {
            return null;
        }

        if(!array_key_exists($input, $messages)) {
            return null;
        }

        if(!array_key_exists($rule, $messages[$input])) {
            return null;
        }

        return $messages[$input][$rule];
    }
}