<?php

namespace UW\Helpers;

use Bitrix\Main\Config\Configuration;
use HL\Base as HLHelper;

/**
 * Хелпер содержит информацию и вспомогательные функции для работы с системой
 *
 * Class CoreHelper
 * @package UW\Helpers
 */
class CoreHelper
{
    /**
     * Код HL-блока События системы
     */
    const HL_CODE_EVENTS = 'Events';

    /**
     * Тип сущности Заявление
     */
    const ENTITY_CODE_REQUEST = 'Requests';
    /**
     * Тип сущности Интернет-приемная
     */
    const ENTITY_CODE_RECEPTION = 'Reception';
    /**
     * Тип сущности Запись на подачу документов
     */
    const ENTITY_CODE_RECORD_ON_DOCS = 'RecordOnDocs';
    /**
     * Тип сущности Запись на прием к руководителю
     */
    const ENTITY_CODE_RECORD_TO_HEAD = 'RecordToHead';
    /**
     * Тип сущности Вопрос эксперту
     */
    const ENTITY_CODE_EXPERT_QUESTION = 'ExpertQuestion';
    /**
     * Тип сущности Сертификат пользователя
     */
    const ENTITY_CODE_CERTIFICATE = 'Certificate';

    /**
     * Получить список событий доступных в системе
     *
     * @return array
     */
    public function getEventsList()
    {
        $hl = HLHelper::initByCode(self::HL_CODE_EVENTS);
        $result = [];
        $params = [];

        foreach ($hl::getList($params)->fetchAll() as $item){
            $result[] = [
                'id' => $item['ID'],
                'name' => $item['UF_NAME'],
                'code' => $item['UF_CODE'],
                'entityType' => $item['UF_ENTITY_TYPE']
            ];
        }

        return $result;
    }

    /**
     * Получить доменное имя
     *
     * @return string
     */
    public function getDomain()
    {
        return Configuration::getValue('domain');
    }

    /**
     * Получить протокол
     *
     * @return string
     */
    public function getProtocol()
    {
        return Configuration::getValue('protocol');
    }
}