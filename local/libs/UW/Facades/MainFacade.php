<?php

namespace UW\Facades;

use UW\Config;
use UW\Helpers\CoreHelper;
use UW\Helpers\SEFHelper;


/**
 * Главный фасад.
 *
 * Содержит общие методы для работы с системой
 *
 * Class MainFacade
 * @package UW\Facades
 */
class MainFacade
{
    /**
     * @var Config
     */
    private $config;

    private $coreHelper;

    /**
     * MainFacade constructor.
     */
    public function __construct()
    {
        $this->config = new Config();
        $this->coreHelper = new CoreHelper();
    }

    /**
     * Получить доменное имя
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->coreHelper->getDomain();
    }

    /**
     * Получить протокол (http/https)
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->coreHelper->getProtocol();
    }

    /**
     * Получить URL используемые в Личном кабинете пользователя
     */
    public function getPrivateAreaLinks()
    {
        return [
            'request' => [
                'detail' => $this->config['privateArea']['pages']['request']['detail'],
                'list' => $this->config['privateArea']['pages']['request']['list'],
                'add' => $this->config['privateArea']['pages']['request']['add']
            ],
            'reception' => [
                'detail' => $this->config['privateArea']['pages']['reception']['detail'],
                'list' => $this->config['privateArea']['pages']['reception']['list'],
            ],
            'expertQuestion' => [
                'detail' => $this->config['privateArea']['pages']['expertQuestion']['detail'],
                'list' => $this->config['privateArea']['pages']['expertQuestion']['list'],
            ],
            'recordOnDocs' => [
                'detail' => $this->config['privateArea']['pages']['recordOnDocs']['detail'],
                'list' => $this->config['privateArea']['pages']['recordOnDocs']['list'],
            ],
            'recordToHead' => [
                'detail' => $this->config['privateArea']['pages']['recordToHead']['detail'],
                'list' => $this->config['privateArea']['pages']['recordToHead']['list'],
            ],
            'certificate' => [
                'list' => $this->config['privateArea']['pages']['certificates'],
            ]
        ];
    }

    /**
     * Получить URL страницы с сервисом проверки статуса заявления/обращения
     * @return mixed
     */
    public function getCheckingStatusServiceLink()
    {
        return $this->config['checkingStatusService'];
    }

    /**
     * Получить URL корневого каталога Личного кабинета
     * @return string
     */
    public function getPrivateAreaFolder()
    {
        return $this->config['privateArea']['folder'];
    }

    /**
     * Форматировать шаблон УРЛ.
     *
     * Заменяет в шаблоне единственное вхождение переменной Идентификатор элемента.
     * Возврашает готовый урл детальной страницы элемента.
     *
     * @param string $urlTemplate Шаблон адреса
     * @param int $elementId Идентификатор элемента
     * @return string
     */
    public function formatPrivateAreaDetailPageURL($urlTemplate, $elementId)
    {
        return (new SEFHelper())->formatPrivateAreaDetailPageURL($urlTemplate, $elementId);
    }


    /**
     * Получить список событий ядра
     *
     * @return array
     */
    public function getEventsList()
    {
        return (new CoreHelper())->getEventsList();
    }
}