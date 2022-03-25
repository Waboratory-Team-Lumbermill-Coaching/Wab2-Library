<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/19/2022
 * Time: 5:00 PM
 */

namespace App\Helpers;

use App\Http\User\UserRepository;
use App\Models\User;
use DateInterval;
use DateTime;
const TAGS = [
    'TITLE' => '<h1>{{title}}</h1>',
    'FULL_TAG' => '<{{tag}}{{attributes}}>{{innerHTML}}</{{tag}}>',
    'SHORT_TAG' => '<{{tag}}{{attributes}}/>',
];

const INLINE_ELEMENTS = [
    'input'
];

class AppHelper {
    public static function renderTitle($title) {
        return str_replace('{{title}}', $title, TAGS['TITLE']);
    }

    /**
     *
     * @param $attributes - assoc array key is attribute value is the value
     * @return string
     */
    public static function generateAttributes($attributes) {
        if(!$attributes || !is_array($attributes)) {
            return '';
        }

        $result = '';

        foreach($attributes as $key => $value) {
            $key = self::parseAttributeName($key);

            if($key === 'name') {
                $value = self::parseNameAttributeValue($value);
            }

            $result .= $key . '="' . $value . '"';

            if($key !== array_key_last($attributes)) {
                $result .= ' ';
            }
        }

        return $result;
    }

    public static function renderTag($tag, $value = '', $attributes = null) {
        $isInline = in_array(strtolower($tag), INLINE_ELEMENTS);
        $tagType = $isInline ? TAGS['SHORT_TAG'] : TAGS['FULL_TAG'];

        $setTag = self::parseTemplate('tag', $tag, $tagType);

        $parsedAttributes = self::generateAttributes($attributes);
        $setAttributes = self::parseTemplate(
            'attributes',
            $parsedAttributes ? ' ' . $parsedAttributes : '',
            $setTag
        );

        return self::parseTemplate('innerHTML', $value, $setAttributes);
    }

    /**
     * @param $tableRows
     * @return mixed
     */
    public static function renderTable($tableRows = null) {
        $headRowValue = '';
        $columns = null;
        $tbody = '';
        if($tableRows && is_array($tableRows)) {
            $columns = [];

            foreach($tableRows as $row) {
                $keysForRow = array_keys($row);

                if(count($keysForRow) > count($columns)) {
                    $columns = $keysForRow;
                }
            }

            foreach($columns as $key => $column) {
                if($key === array_key_last($columns)) {
                    $headRowValue .= self::renderTag('th', $column);
                    continue;
                }
                $headRowValue .= self::renderTag('th', $column) . PHP_EOL;
            }

            $tBodyRows = '';

            foreach($tableRows as $row) {
                $rowData = '';

                foreach($row as $item) {
                    if(!$item) {
                        continue;
                    }
                    $rowData .= self::renderTag('td', $item) . PHP_EOL;
                }

                if(!$rowData) {
                    continue;
                }
                $tr = self::renderTag('tr', $rowData);
                $tBodyRows .= $tr;
            }

            if($tBodyRows) {
                $tbody = self::renderTag('tbody', PHP_EOL . $tBodyRows . PHP_EOL);
            }
        }

        $headRow = self::renderTag('tr', PHP_EOL . $headRowValue . PHP_EOL);
        $head = self::renderTag('thead', PHP_EOL . $headRow . PHP_EOL);

        $table = self::renderTag('table', PHP_EOL . $head . $tbody . PHP_EOL);

        return $table;
    }

    public static function parseTemplate($key, $replace, $where) {
        return str_replace('{{' . $key . '}}', $replace, $where);
    }

    public static function redirect($url) {
        return header('Location: ' . url($url));
    }

    /**
     * @param User $user
     * @return bool
     * @throws \Exception
     */
    public static function storeAuth(User $user) {
        $_SESSION['user_id'] = $user->id;
        $time = AppHelper::dateInFuture(60, 'M');
        $_SESSION['expires_on'] = $time->format('Y-m-d H:i');

        return true;
    }

    public static function authUser() {
        if(!isset($_SESSION)) {
            return null;
        }

        if(!count($_SESSION)) {
            return null;
        }

        $now = date('Y-m-d H:i:s');

        if(!array_key_exists('expires_on', $_SESSION) || $_SESSION['expires_on'] < $now) {
            return null;
        }

        if(!array_key_exists('user_id', $_SESSION)) {
            return null;
        }

        $userRepository = new UserRepository();

        $user = $userRepository->findById($_SESSION['user_id']);

        if(!$user) {
            return null;
        }

        return $user;
    }

    public static function logout() {
        $_SESSION['user_id'] = null;
        $_SESSION['expires_on'] = null;
    }

    private static function parseAttributeName($key) {
        $key = strtolower($key);
        $keyItems = explode('_', $key);

        return implode('', $keyItems);
    }

    private static function parseNameAttributeValue($value) {
        // exampleCase
        $separateWords = preg_split('/(?=[A-Z])/', $value);
        // example_Case
        $convertToSnakeCase = implode('_', $separateWords);

        // example_case
        return strtolower($convertToSnakeCase);
    }

    private static function dateInFuture($time, $tiemType) {
        $date = new DateTime();
        $date->add(new DateInterval('PT' . $time . $tiemType));

        return $date;
    }

    public function encode_arr($data) {
        return base64_encode(serialize($data));
    }

    public function decode_arr($data) {
        return unserialize(base64_decode($data));
    }
}