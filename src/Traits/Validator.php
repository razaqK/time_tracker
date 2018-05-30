<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 5/25/18
 * Time: 8:29 AM
 */

namespace App\Traits;


use App\Constants\ResponseMessages;
use App\Libs\Utils;

trait Validator
{
    protected function validateParameters(array $data, array $rules)
    {
        $validated = \GUMP::is_valid($data, $rules);
        if ($validated === true) {
            return ['status' => true];
        }

        $messageList = Utils::stripHtmlTags($validated);
        $message = sprintf(ResponseMessages::INVALID_PARAM, implode(',', $messageList));
        return ['status' => false, 'message' => $message, 'data' => $messageList];
    }
}