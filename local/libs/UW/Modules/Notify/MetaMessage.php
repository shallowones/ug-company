<?php
namespace UW\Modules\Notify;


/**
 * Сообщение с мета-данными для провайдеров, которые сохраняют сообщения в собственную БД
 * с возможностью последующей сортировки/фильтрации по мета-данным
 *
 * Class MetaMessage
 * @package UW\Modules\Notify
 */
class MetaMessage
{
    /**
     * Шаблон с данными
     * @var Template
     */
    protected $template;
    /**
     * Получатели
     * @var array
     */
    protected $consumers;
    /**
     * Событие
     * @var Event
     */
    protected $event;

    /**
     * MetaMessage constructor.
     * @param Template $template Отформатированный шаблон с данными
     * @param Event $event
     * @param array $consumers Получатели
     */
    function __construct(Template $template, Event $event, array $consumers)
    {
        $this->template = $template;
        $this->consumers = $consumers;
        $this->event = $event;
    }

    /**
     * @return mixed
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

    /**
     * @return Event
     */
    public function getEvent()
    {
        return $this->event;
    }
}