<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 05.04.2017
 * Time: 10:44
 */

namespace UW\Modules\Notify\Exceptions;

/**
 * Исключения возникающие при работе с событием
 *
 * Диапазон кодов ошибок [150..159]
 *
 * Class ConfigException
 * @package UW\Modules\Notify\Exceptions
 */
class EventException extends NotifyException
{
    const ENTITY_ID_NOT_FOUND = 150;
    const ENTITY_CODE_NOT_FOUND = 151;
}