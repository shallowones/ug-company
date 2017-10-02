<?php

namespace UW\Modules\Notify\Exceptions;

/**
 * Исключения возникающие при нарушении логики работы с конфигурацией
 *
 * Диапазон кодов ошибок [100..119]
 *
 * Class ConfigException
 * @package UW\Modules\Notify\Exceptions
 */
class ConfigException extends NotifyException
{
    /**
     * Регистрация не допустимого события
     */
    const EVENT_UNACCEPTABLE_REGISTER = 101;
    /**
     * Регистрация не допустимого провайдер
     */
    const PROVIDER_UNACCEPTABLE_REGISTER = 102;
    /**
     * Не удалось найти провайдер по идентификатору
     */
    const PROVIDER_NOT_FOUND = 103;
}