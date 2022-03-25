<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/19/2022
 * Time: 5:15 PM
 */
require_once __DIR__ . '\..\Rules\loadRules.php';
require_once __DIR__ . '\AppHelper.php';
require_once __DIR__ . '\NotificationHelper.php';
require_once __DIR__ . '\DrawerHelper.php';
require_once __DIR__ . '\ValidatorHelper.php';

const CONFIG = [
    'APP_URL' => 'http://library-exam.sasho.wab'
];

function dd($value) {
    if(func_num_args() > 0) {
        $args = func_get_args();
        foreach($args as $arg) {
            echo '<pre>' . print_r($arg) . '</pre>';
            echo '<br>';
            echo '<br>';
        }
        exit;
    }
}

function url($value, $params = [], $secure = false) {
    if(strlen($value) && $value[0] === '/') {
        $value = substr($value, 1);
    }

    $paramsQuery = '';
    foreach($params as $key => $pValue) {
        $paramsQuery .= "$key=$pValue";
    }

    return CONFIG['APP_URL'] . '/' . $value . '.php' . ($paramsQuery ? '?' . $paramsQuery : '');
}