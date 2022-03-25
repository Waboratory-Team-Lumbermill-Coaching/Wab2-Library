<?php
/**
 * Created by PhpStorm.
 * User: kliko
 * Date: 2/20/2022
 * Time: 12:44 PM
 */

namespace App\Helpers;

class DrawerHelper {
    public static function renderPage($pageData) {
        if(!$pageData || !is_array($pageData)) {
            echo '';
            return;
        }

        $pageHTML = '';

        if(array_key_exists('title', $pageData)) {
            $pageHTML .= AppHelper::renderTitle($pageData['title']);
        }

        $pageHTML .= self::renderNotification();

        if(array_key_exists('menu', $pageData)) {
            $pageHTML .= self::renderMenu($pageData['menu']);
        }

        if(array_key_exists('elements', $pageData)) {
            $pageHTML .= self::renderPageElements($pageData['elements']);
        }

        echo $pageHTML;
    }

    private static function renderPageElements($elements) {
        if(!$elements && !is_array($elements)) {
            return '';
        }

        $html = '';

        foreach($elements as $element) {
            $tag = $element['tag'];
            $attributes = isset($element['attributes']) ? $element['attributes'] : null;
            $elmValue = isset($element['value']) ? $element['value'] : null;
            $elmChildren = isset($element['children']) ? $element['children'] : null;

            if(is_array($elmValue)) {
                $parsedPattern = self::parseTagValueByPattern($elmValue);

                $html .= $parsedPattern;

                continue;
            }

            if(is_array($elmChildren)) {
                $htmlChildren = self::renderPageElements($elmChildren);
                $html .= self::renderElement($tag, $htmlChildren, $attributes) . ' ';

                continue;
            }

            $html .= self::renderElement($tag, $elmValue, $attributes) . ' ';
        }

        return $html;
    }

    private static function parseTagValueByPattern($data) {
        $pattern = $data['template'];
        $patternParseItems = $data['template_parse'];

        foreach($patternParseItems as $key => $parseItem) {
            if(is_array($parseItem)) {
                $parseItemElements = $parseItem['elements'];
                $parsedItemHTML = '';
                foreach($parseItemElements as $tag => $elementDetails) {
                    $parsedItemHTML .= AppHelper::renderTag(
                        $tag,
                        $elementDetails['value'],
                        isset($elementDetails['attributes']) ? $elementDetails['attributes'] : null
                    );
                }

                $pattern = AppHelper::parseTemplate($key, $parsedItemHTML, $pattern);
            }
        }

        return $pattern;
    }

    private static function renderElement($tag, $value, $attributes = null) {
        return AppHelper::renderTag($tag, $value, $attributes ? $attributes : null);
    }

    private static function renderNotification() {
        if(NotificationHelper::hasNotification()) {
            return NotificationHelper::getNotification();
        }

        return '';
    }

    private static function renderMenu($menu) {
        $menuHTML = '';

        $keys = array_keys($menu);
        $lastKey = array_pop($keys);

        foreach($menu as $key => $item) {
            $menuHTML .= AppHelper::renderTag('a', $item['text'], [
                'href' => $item['url']
            ]);

            if($key !== $lastKey) {
                $menuHTML .= ' | ';
            }
        }

        $menuHTML = AppHelper::parseTemplate('menu_placeholder',
            $menuHTML,
            AppHelper::renderTag('p', '{{menu_placeholder}}')
        );

        return $menuHTML;
    }
}