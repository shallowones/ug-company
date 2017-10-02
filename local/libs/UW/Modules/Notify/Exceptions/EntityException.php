<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 05.04.2017
 * Time: 10:44
 */

namespace UW\Modules\Notify\Exceptions;

/**
 * Исключения возникающие при работе с сущностями
 *
 * Диапазон кодов ошибок [140..149]
 *
 * Class ConfigException
 * @package UW\Modules\Notify\Exceptions
 */
class EntityException extends NotifyException
{
    const ENTITY_NOT_FOUND = 140;

    const ENTITY_WRONG_TYPE = 141;
}