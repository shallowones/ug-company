<?php

namespace UW\Facades;

use Bitrix\Main\Type\DateTime;
use UW\Helpers\RequestHelper;
use UW\HLHelper;
use UW\Modules\Notify\Exceptions\EntityException;

/**
 * Фасад сущности заявления.
 *
 * Содержит методы для работы с заявленями.
 *
 * Class RequestFacade
 * @package UW\Facades
 */
class RequestFacade
{
    /**
     * @var RequestHelper
     */
    protected $helper;

    function __construct()
    {
        $this->helper = new RequestHelper();
    }

    /**
     * Получить список статусов
     *
     * @return array
     */
    public function getStatuses()
    {
        return $this->helper->getStatuses();
    }

    /**
     * Получить статус "Заявление направлено"
     */
    public function getStatusSent()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_SENT);
    }

    /**
     * Получить статус "Подача приостановлена"
     */
    public function getStatusSuspended()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_SUSPENDED);
    }

    /**
     * Получить статус "Отказ"
     * @return array
     */
    public function getStatusReject()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_REJECT);
    }

    /**
     * Получить статус "Возврат"
     * @return array
     */
    public function getStatusReturn()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_RETURN);
    }

    /**
     * Получить стаутс "На рассмотрении"
     * @return array
     */
    public function getStatusPending()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_PENDING);
    }

    /**
     * Получить статус "Заявление аннулировано"
     * @return array
     */
    public function getStatusVoid()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_VOID);
    }

    /**
     * Получить статус "Нет оплаты"
     * @return array
     */
    public function getStatusNoPayment()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_NO_PAYMENT);
    }

    /**
     * Получить статус "Оказание услуги"
     * @return array
     */
    public function getStatusProvide()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_PROVIDE);
    }

    /**
     * Получить статус "Подготовка заключения"
     * @return array
     */
    public function getStatusPreparation()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_PREPARATION);
    }

    /**
     * Получить статус "Услуга оказана"
     * @return array
     */
    public function getStatusComplete()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_COMPLETE);
    }

    /**
     * Получить статус "Услуга не оказана"
     * @return array
     */
    public function getStatusNotProvided()
    {
        return $this->helper->getStatus(RequestHelper::STATUS_CODE_NOT_PROVIDED);
    }

    /**
     * Получить список услуг
     * @return array
     */
    public function getServiceList()
    {
        return $this->helper->getServiceList();
    }

    /**
     * Получить данные по заявлению
     * @param $id
     * @return array
     * @throws EntityException
     */
    public function getItem($id)
    {
        $class = HLHelper::initByCode(RequestHelper::HL_CODE);
        $params = [
            'select' => [
                'id'         => 'ID',
                'status'     => 'UF_STATUS',
                'ownerId'    => 'UF_SENDER_ID',
                'number'     => 'UF_NUM',
                'dateSend'   => 'UF_DATE_REQUEST',
                'dateCreate' => 'UF_DATE_CREATE',
                'files'      => 'UF_FILES',
                'dateStatus' => 'UF_DATE_STATUS'
            ],
            'filter' => [
                'ID' => $id
            ]
        ];
        $item = $class::getList($params)->fetch();

        if (!$item) {
            $item = [];
        }

        if ($item['dateSend'] && $item['dateSend'] instanceof DateTime) {
            $item['fillingDate'] = (new \DateTime())->setTimestamp($item['dateSend']->getTimestamp());
        } else {
            $item['fillingDate'] = (new \DateTime())->setTimestamp($item['dateCreate']->getTimestamp());
        }


        return $item;
    }
}