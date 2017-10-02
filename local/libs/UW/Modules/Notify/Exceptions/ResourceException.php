<?php

namespace UW\Modules\Notify\Exceptions;

/**
 * Исключения возникающие при работе с ресурсами (БД, файлы)
 *
 * Диапазон кодов ошибок [120..139]
 *
 * Class ResourceException
 * @package UW\Modules\Notify\Exceptions
 */
class ResourceException extends NotifyException
{
    /**
     * Не удалось добавить значение в БД
     */
    const DB_CANT_ADD_VALUE = 120;
    /**
     * Не удалось удалить значение из БД
     */
    const DB_CANT_DELETE_VALUE = 121;
}