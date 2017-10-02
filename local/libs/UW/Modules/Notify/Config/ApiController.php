<?php

namespace UW\Modules\Notify\Config;


use ReflectionClass;
use UW\Config;
use UW\Modules\Notify\Formatters\FormatterFactory;
use UW\Modules\Notify\Specifications\ISpecificationInforming;
use UW\Modules\Notify\Specifications\SpecificationFactory;

/**
 * Обслуживает REST API вызовы
 * 
 * Class ApiController
 * @package UW\Modules\Notify\Config
 */
class ApiController
{
    private $config;
    private $event;
    private $template;
    private $provider;
    private $rule;
    private $formatterFactory;
    private $specificationFactory;

    function __construct()
    {
        $this->config = new Config();
        $this->event = new Event();
        $this->template = new Template();
        $this->provider = new Provider();
        $this->rule = new Rule();
        $this->formatterFactory = new FormatterFactory();
        $this->specificationFactory = new SpecificationFactory();
    }

    /**
     * Получить список сущностей
     *
     * Набор сущностей, который поддерживает модуль уведомлений
     *
     * @return mixed
     */
    public function getEntitiesList()
    {
        return $this->config['Notify']['entities'];
    }

    /**
     * Получить список событий для сущности
     *
     * @param string $entityCode Код сущности
     * @return array
     */
    public function getEventsByEntity($entityCode)
    {
        return $this->event->getList($entityCode);
    }

    /**
     * Зарегистрировать событие для сущности
     *
     * @param $eventCode
     * @param $entityCode
     * @return array
     */
    public function registerEvent($eventCode, $entityCode)
    {
        return [
            'eventId' => $this->event->add($eventCode, $entityCode)
        ];
    }

    /**
     * Отключить прослушку события
     *
     * @param $eventId
     */
    public function removeEvent($eventId)
    {
        $this->event->delete($eventId);
    }

    /**
     * Получить список провайдеров
     *
     * @param $eventId
     * @return array
     */
    public function getProviders($eventId)
    {
        return $this->provider->getList($eventId);
    }

    /**
     * Подписать провайдер на событие
     *
     * @param $eventId
     * @param $providerCode
     * @return array
     */
    public function registerProvider($eventId, $providerCode)
    {
        return [
            'providerId' => $this->provider->add($eventId, $providerCode)
        ];
    }

    /**
     * Отключить провайдер
     *
     * @param $providerId
     */
    public function removeProvider($providerId)
    {
        $this->provider->delete($providerId);
    }

    /**
     * Получить детальную информацию по провайдеру
     *
     * @param $providerId
     * @return array
     */
    public function getProviderInfo($providerId)
    {
        return $this->provider->getDetail($providerId);
    }

    /**
     * Сохранить информацию по провайдеру
     *
     * @param $providerId
     * @param $providerData
     */
    public function saveProviderInfo($providerId, $providerData)
    {
        if (!isset($providerData['template'])) {
            $providerData['template'] = [];
        }

        if(!isset($providerData['rules'])){
            $providerData['rules'] = [];
        }


        $this->template->save($providerId, $providerData['template']);

        foreach ($providerData['rules'] as $arRule) {
            $this->rule->save($providerId, $arRule, $arRule['id']);
        }
    }

    /**
     * Удалить правило
     * @param $ruleId
     */
    public function removeRule($ruleId)
    {
        $this->rule->delete($ruleId);
    }

    /**
     * Получить список доступных логических операций для утверждений
     * @return mixed
     */
    public function getAssertionsLogicList()
    {
        return $this->config['Notify']['assertionLogic'];
    }

    /**
     * Получить список переменных, используемых в алгоритмах
     *
     * @param $entityCode
     * @return array
     */
    public function getTemplateAlgorithmVariables($entityCode)
    {
        $result = [];

        foreach ($this->formatterFactory->createEntityFormatters($entityCode) as $formatter){
            $arVariable = $formatter->getVariableInfo();
            reset($arVariable);

            $result[] = [
                'code' => key($arVariable),
                'value' => current($arVariable)
            ];
        }

        return $result;
    }

    /**
     * Получить список переменных, используемых в списке получателей
     * @return array
     */
    public function getConsumersAlgorithmVariables()
    {
        $result = [];

        foreach ($this->formatterFactory->createConsumersFormatters() as $formatter){
            $arVariable = $formatter->getVariableInfo();
            reset($arVariable);

            $result[] = [
                'code' => key($arVariable),
                'value' => current($arVariable)
            ];
        }

        return $result;
    }

    /**
     * Получить список утверждений для сущности
     *
     * @param $entityCode
     * @return array
     */
    public function getAssertionsList($entityCode)
    {
        if(empty($entityCode)){
            return [];
        }

        $result = [];

        foreach ($this->specificationFactory->createEntitySpecificationsList($entityCode) as $specification){
            /**
             * @var ISpecificationInforming $specification
             */
            $result[] = [
                'code' => (new ReflectionClass($specification))->getShortName(),
                'name' => $specification->getName()
            ];
        }

        return $result;
    }
}