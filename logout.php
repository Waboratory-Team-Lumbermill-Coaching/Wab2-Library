<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/21/2022
 * Time: 4:46 PM
 */
require_once __DIR__ . '\common.php';
session_start();

use App\Helpers\AppHelper;


AppHelper::logout();
$user = \App\Helpers\AppHelper::authUser();

if($user) {
    return \App\Helpers\AppHelper::redirect('profile');
}

\App\Helpers\AppHelper::redirect('login');
