<?php

namespace UW\Modules\Notify;

use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Formatters\Analyzer;
use UW\Modules\Notify\Formatters\FormatterFactory;

/**
 * Класс содержит в себе данные конфигурации конкретного провайдера, а так же инструменты
 * для обработки шаблона, правил и получателей.
 *
 * Class ProviderData
 * @package UW\Modules\Notify
 */
class ProviderConfigData
{
    /**
     * Код провайдера
     * @var string
     */
    protected $providerCode;
    /**
     * Код события
     * @var string
     */
    protected $eventCode;
    /**
     * Код сущности
     * @var string
     */
    protected $entityCode;
    /**
     * Шаблон
     * @var Template
     */
    protected $template;
    /**
     * Коллекция правил
     * @var Rule[]
     */
    protected $rules;
    /**
     * Сущность, в которой произошло событие
     * @var IEntity
     */
    protected $triggeredEntity;
    /**
     * Произошедшее событие
     * @var Event
     */
    protected $triggeredEvent;
    /**
     * Список получателей
     * @var array
     */
    protected $consumers;

    /**
     * Фабрика дял создания алгоритмов форматирования
     * @var FormatterFactory
     */
    private $formatterFactory;
    /**
     * @var Analyzer
     */
    private $analyzer;

    /**
     * ProviderData constructor.
     * @param $providerCode
     * @param $eventCode
     * @param $entityCode
     * @param Template $template
     * @param $rules
     */
    function __construct(
        $providerCode,
        $eventCode,
        $entityCode,
        Template $template,
        array $rules
    ) {
        $this->providerCode = $providerCode;
        $this->eventCode = $eventCode;
        $this->entityCode = $entityCode;
        $this->template = $template;

        foreach ($rules as $rule) {
            if ($rule instanceof Rule) {
                $this->rules[] = $rule;
            }
        }

        $this->formatterFactory = new FormatterFactory();
        $this->analyzer = new Analyzer();
        $this->consumers = [];
    }

    /**
     * Установить сущность, в которой произошло событие
     * @param IEntity $triggeredEntity
     * @return $this
     */
    public function setTriggeredEntity(IEntity $triggeredEntity)
    {
        $this->triggeredEntity = $triggeredEntity;

        return $this;
    }

    /**
     * Установить произошедщее событие
     * @param Event $triggeredEvent
     * @return $this
     */
    public function setTriggeredEvent(Event $triggeredEvent)
    {
        $this->triggeredEvent = $triggeredEvent;

        return $this;
    }

    /**
     * Получить код события
     * @return string
     */
    public function getEventCode()
    {
        return $this->eventCode;
    }

    /**
     * Получить код сущности
     * @return string
     */
    public function getEntityCode()
    {
        return $this->entityCode;
    }

    /**
     * Получить шаблон
     * @return Template
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Получить список получателей
     *
     * @return array
     */
    public function getConsumers()
    {
        return $this->consumers;
    }

    /**
     * Применить имеющиеся правила
     *
     * Правила применяются к сущности, в которой произошло событие. В результате, формируется
     * коллекция получателей.
     *
     * При каждом вызове метода, коллекция получателей ПЕРЕЗАПИСЫВАЕТСЯ!
     *
     * @return $this
     */
    public function applyRules()
    {
        $arConsumers = [];

        foreach ($this->rules as $rule) {
            if ($rule->complyWith($this->triggeredEntity, $this->triggeredEvent)) {
                $arConsumers = array_merge($arConsumers, $rule->getConsumers());
            }
        }

        $this->consumers = $arConsumers;

        return $this;
    }


    /**
     * Применить алгоритм форматирования шаблона
     *
     * @return $this
     */
    public function applyTemplateAlgorithm()
    {
        $data = [
            'title' => $this->template->getTitle(),
            'body'  => $this->template->getBody()
        ];

        $formattedData = $this->formatterFactory->create()
            ->setDataFormatting($data)
            ->setEntity($this->triggeredEntity)
            ->setEvent($this->triggeredEvent)
            ->setFormatters($this->analyzer->getFormatters($data, $this->entityCode))
            ->execute();

        $this->template = $this->template->setTitle($formattedData['title'])->setBody($formattedData['body']);

        return $this;
    }

    /**
     * Применить алгоритм форматирования списка получателей
     * @return $this
     */
    public function applyConsumersAlgorithm()
    {
        if (!$this->consumers) {
            return $this;
        }

        $this->consumers = $this->formatterFactory->create()
            ->setDataFormatting($this->consumers)
            ->setEntity($this->triggeredEntity)
            ->setEvent($this->triggeredEvent)
            ->setFormatters($this->analyzer->getFormatters($this->consumers))
            ->execute();

        return $this;
    }
}