<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/20/2022
 * Time: 10:57 AM
 */

namespace App\Helpers;

class NotificationHelper {
    private const TYPE = [
        'SUCCESS' => [
            'KEY' => 'SUCCESS',

            'ATTRIBUTES' => [
                'style' => 'color: green;'
            ],

            'SESSION' => [
                'KEY' => 'success',
                'IS_FLASH' => true
            ],
        ],

        'ERROR' => [
            'KEY' => 'ERROR',

            'ATTRIBUTES' => [
                'style' => 'color: red;'
            ],

            'SESSION' => [
                'KEY' => 'error',
                'IS_FLASH' => true
            ],
        ],
    ];

    public static function success($message) {
        return self::buildNotification($message, self::TYPE['SUCCESS']['KEY']);
    }

    public static function error($message) {
        return self::buildNotification($message, self::TYPE['ERROR']['KEY']);
    }

    public static function getNotification() {
        $notification = self::getCurrentNotification();

        if(!$notification) {
            return null;
        }

        if($notification['is_flash']) {
            self::clearOldNotification();
        }

        return $notification['elm'];
    }

    public static function hasNotification() {
        return !!self::getCurrentNotification();
    }

    public static function clear() {
        self::clearOldNotification();
    }

    private static function getCurrentNotification() {
        if(!array_key_exists('notification_details', $_SESSION)) {
            return null;
        }

        $notificationDetails = $_SESSION['notification_details'];

        if(!$notificationDetails) {
            return null;
        }

        return $notificationDetails;
    }

    private static function buildNotification($message, $type) {
        self::clearOldNotification();

        $config = self::TYPE[$type];
        $sessionConfig = $config['SESSION'];

        $_SESSION[$sessionConfig['KEY']] = $message;
        $_SESSION['notification_details'] = [
            'type' => $type,
            'elm' => AppHelper::renderTag('p', $message, $config['ATTRIBUTES']),
            'is_flash' => $sessionConfig['IS_FLASH']
        ];

        return AppHelper::renderTag('p', $message, $config['ATTRIBUTES']);
    }

    private static function clearOldNotification() {
        $currentNotification = self::getCurrentNotification();


        if(!$currentNotification) {
            return;
        }

        $curConfigType = $currentNotification['type'];
        $curConfig = self::TYPE[$curConfigType];
        $curSessionConfig = $curConfig['SESSION'];

        unset($_SESSION[$curSessionConfig['KEY']]);
        unset($_SESSION['notification_details']);
    }
}