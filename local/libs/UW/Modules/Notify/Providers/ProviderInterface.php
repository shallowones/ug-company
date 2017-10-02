<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 14.02.2017
 * Time: 11:25
 */

namespace UW\Modules\Notify\Providers;


interface ProviderInterface
{
    /**
     * Получить код провайдера
     *
     * @return string
     */
    public function getCode();

    /**
     * Получить имя провайдера
     *
     * @return string
     */
    public function getName();

    /**
     * Получить время последнего запроса
     *
     * @return \DateTime
     */
    public function getLastQueryDate();

    /**
     * Запустить провайдера
     * @return mixed
     */
    public static function run();
}