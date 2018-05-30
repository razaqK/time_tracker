<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 29/12/2017
 * Time: 9:39 PM
 */

namespace App\Constants;


class ResponseMessages
{
    const INTERNAL_SERVER_ERROR = 'We seem to be experiencing a problem with our server. Do you mind trying later?';
    const TECHNICAL_ISSUE = 'It’s not you. It’s us. Give it another try, please.';
    const INVALID_PARAM = 'Bad request - %s. Check and try again';
    const NOT_FOUND = 'No %s was found for the request.';
    const NOT_ALLOWED = 'Seem the resource is not allowed.';
}