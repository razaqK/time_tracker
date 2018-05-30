<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 5/25/18
 * Time: 8:27 AM
 */

namespace App\Libs;


class Utils
{
    /**
     * @param $params
     * @return array
     */
    public static function stripHtmlTags($params)
    {
        return array_map(function ($v) {
            return strip_tags($v);
        }, $params);
    }
}