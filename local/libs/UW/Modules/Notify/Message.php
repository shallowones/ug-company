<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 17.02.2017
 * Time: 11:45
 */

namespace UW\Modules\Notify;

/**
 * Сообщение, которое передается из модуля Уведомлений для провайдеров
 * занимающихся точечной рассылкой уведомлений
 *
 * Class Message
 * @package UW\Modules\Notify
 */
class Message
{
    /**
     * @var Template
     */
    protected $template;
    /**
     * @var array
     *
     */
    protected $consumers;

    function __construct(Template $template, $consumers)
    {
        $this->template = $template;
        $this->consumers = $consumers;
    }

    /**
     * @return array
     */
    public function getConsumers()
    {
        return $this->consumers;
    }

    /**
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }
}