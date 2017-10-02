<?php

namespace UW\Modules\Notify\Formatters\Common;


use UW\Modules\Notify\Entities\IEntity;
use UW\Modules\Notify\Event;
use UW\Modules\Notify\Formatters\IStrategy;
use UW\Modules\Notify\Formatters\IStrategyInforming;
use UW\Modules\Notify\Formatters\Replacer;

/**
 * Реализует алгоритм подстановки ФИО автора действия (текущего пользователя,
 * который создал заявлений (заявитель), изменил статус (сотрудник) и т.д.)
 *
 * Class ItemCreateFormatter
 * @package UW\Modules\Notify\Formatters\Reception
 */
class AuthorActionFIOFormatter implements IStrategy, IStrategyInforming
{
    /**
     * Информация об обрабатываемой переменной
     * @var array
     */
    protected $variableInfo = [
        '#authorActionFIO' => 'ФИО автора действия'
    ];

    /**
     * Алгоритм форматирования данных
     *
     * @param array $dataFormatting Данные для форматирования
     * @param IEntity $entity
     * @param Event $event
     * @return array
     * @internal param array $additionalInformation Дополнительные данные
     */
    public function algorithm(array $dataFormatting, IEntity $entity, Event $event)
    {
        $arUser = \CUser::GetList(
            $by = 'id',
            $order = 'asc',
            ['ID' => $event->getInitiatorId()],
            ['FIELDS' => ['LAST_NAME', 'NAME', 'SECOND_NAME']]
        )->GetNext();

        $strFIO = "{$arUser['LAST_NAME']} {$arUser['NAME']} {$arUser['SECOND_NAME']}";

        $replacer = new Replacer('#authorActionFIO', trim($strFIO));

        return [
            'title' => $replacer->run($dataFormatting['title']),
            'body'  => $replacer->run($dataFormatting['body'])
        ];
    }

    /**
     * Получить описание переменной, которая обрабатывается в алгоритме
     * @return array
     */
    public function getVariableInfo()
    {
        return $this->variableInfo;
    }
}