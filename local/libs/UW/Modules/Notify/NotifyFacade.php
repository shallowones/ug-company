<?php

namespace UW\Modules\Notify;

use UW\Modules\Notify\Config\ConfigController;
use UW\Modules\Notify\Providers\ActivityProvider;
use UW\Modules\Notify\Providers\NoticeProvider;

/**
 * Фасад для работы с модулем уведомлений
 *
 * Class NotifyFacade
 * @package UW\Modules\Notify
 */
class NotifyFacade
{
    /**
     * @var Controller
     */
    protected $controller;
    /**
     * @var ConfigControllers
     */
    protected $configController;
    /**
     * @var NoticeProvider
     */
    protected $providerNotice;
    /**
     * @var ActivityProvider
     */
    protected $providerAction;

    private $mapNotify = [
        'id'            => 'ID',
        'elementId'     => 'UF_ELEMENT_ID',
        'date'          => 'UF_DATE',
        'elementType'   => 'UF_ELEMENT_TYPE',
        'isRead'        => 'UF_READ',
        'consumerId'    => 'CONSUMER_ID',
        'consumerGroup' => 'CONSUMER_GROUP',
    ];

    private $mapAction = [
        'elementId'   => 'UF_ITEM_ID',
        'date'        => 'UF_DATE',
        'elementType' => 'UF_ITEM_TYPE',
        'userId'      => 'UF_USER_ID',
    ];

    /**
     * Проверить, является ли массив ассоциативным
     *
     * @param array $arr
     * @return bool
     */
    private function isAssoc(array $arr)
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * NotifyFacade constructor.
     */
    function __construct()
    {
        $this->controller = new Controller();
        $this->providerNotice = new NoticeProvider();
        $this->providerAction = new ActivityProvider();
        $this->configController = new ConfigController();
    }

    /**
     * Заменить транзитные ключи
     *
     * @param array $params Параметры для преобразования
     * @param array $map Карта для преобразоваиня
     * @return array
     */
    protected function replaceTransitKeys($params, $map)
    {
        $result = [];

        foreach ($params as $instruction => $iValue) {

            if('limit' == $instruction || 'offset' == $instruction){
                $result[ $instruction ] = $iValue;
                continue;
            }

            if (!is_array($iValue) || empty($iValue)) {
                continue;
            }

            if ($this->isAssoc($iValue)) {
                foreach ($iValue as $key => $value) {
                    $convertedKey = $key;
                    $fConverted = false;

                    foreach ($map as $oldKey => $newKey) {
                        if (false !== strpos($convertedKey, $oldKey)) {
                            $convertedKey = str_replace($oldKey, $newKey, $convertedKey);
                            $fConverted = true;

                            break;
                        }

                    }

                    if ($fConverted) {
                        $result[ $instruction ][ $convertedKey ] = $value;
                    }
                }
            } else {
                $result[ $instruction ] = $iValue;
            }
        }

        return $result;
    }

    /**
     * Получить список уведомлений
     *
     * Ключи, которые могут быть использованы при фильтрации, выборке и сортировке: <br/><br/>
     *  elementId - Идентификатор элемента <br/>
     *  elementType - Тип сущности элемента <br/>
     *  date - Дата уведомления <br/>
     *  isRead - Прочитано или нет <br/>
     *  consumerId - Идентификатор получателя <br/>
     *  consumerGroup - Группа получателя <br/>
     *
     * @param $params
     * @return array
     */
    public function getNoticeList($params = [])
    {
        return $this->providerNotice->getItems($this->replaceTransitKeys($params, $this->mapNotify));
    }

    /**
     * Получить количество уведомлений
     *
     * Ключи, которые могут быть использованы при фильтрации: <br/><br/>
     *  elementId - Идентификатор элемента <br/>
     *  elementType - Тип сущности элемента <br/>
     *  date - Дата уведомления <br/>
     *  isRead - Прочитано или нет <br/>
     *  consumerId - Идентификатор получателя <br/>
     *  consumerGroup - Группа получателя <br/>
     *
     * @param $params
     * @return integer
     */
    public function getNoticeCount($params = [])
    {
        return $this->providerNotice->getItemsCount($this->replaceTransitKeys($params, $this->mapNotify));
    }

    /**
     * Получить код группы получателей - Клиенты
     * @return string
     */
    public function getConsumerClientGroupCode()
    {
        return NoticeProvider::CONSUMER_GROUP_CLIENT;
    }

    /**
     * Получить код группы получателей - Персонал
     * @return string
     */
    public function getConsumerStaffGroupCode()
    {
        return NoticeProvider::CONSUMER_GROUP_STAFF;
    }

    /**
     * Установить для уведомления флаг "Прочитано/Не прочитано"
     *
     * @param $noticeId
     * @param $isRead
     */
    public function setNoticeIsRead($noticeId, $isRead)
    {
        $this->providerNotice->setIsRead($noticeId, $isRead);
    }

    /**
     * Получить список действий пользователей
     *
     * Ключи, которые могут быть использованы при фильтрации, выборке и сортировке: <br/><br/>
     *  elementId - Идентификатор элемента <br/>
     *  elementType - Тип сущности элемента <br/>
     *  date - Дата уведомления <br/>
     *
     * @param $params
     * @return array
     */
    public function getActionList($params = [])
    {
        return $this->providerAction->getItems($this->replaceTransitKeys($params, $this->mapAction));
    }

    /**
     * Получить количество действий пользователей
     *
     * Ключи, которые могут быть использованы при фильтрации: <br/><br/>
     *  elementId - Идентификатор элемента <br/>
     *  elementType - Тип сущности элемента <br/>
     *  date - Дата уведомления <br/>
     *
     * @param $params
     * @return integer
     */
    public function getActionCount($params = [])
    {
        return $this->providerAction->getItemsCount($this->replaceTransitKeys($params, $this->mapAction));
    }

    /**
     * Получить список событий, который использует модуль
     */
    public function getEventHandlers()
    {
        return Handler::getHandlers();
    }
}