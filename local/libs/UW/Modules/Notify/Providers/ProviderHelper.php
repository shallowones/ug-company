<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 16.02.2017
 * Time: 17:54
 */

namespace UW\Modules\Notify\Providers;


use Bitrix\Main\Type\DateTime;
use HL\Base as HLHelper;

/**
 * Класс содержит вспомогательные функции для работы провайдеров уведомлений
 * Class ProviderHelper
 * @package UW\Modules\Notify\Providers
 */
class ProviderHelper
{
    const HL_CODE = 'NoticeProvidersData';

    /**
     * Подготовить праметры для фильтрации HL-блока
     *
     * @param $params
     * @return array
     */
    public function prepareParams($params)
    {
        $arParams = [];

        if(!empty($params['select'])){
            $arParams['select'] = $params['select'];
        }

        if(!empty($params['filter'])){
            $arParams['filter'] = $params['filter'];
        }

        if(!empty($params['order'])){
            $arParams['order'] = $params['order'];
        }

        if(!empty($params['limit'])){
            $arParams['limit'] = $params['limit'];
        }

        if(!empty($params['offset'])){
            $arParams['offset'] = $params['offset'];
        }

        if(!empty($params['runtime'])){
            $arParams['runtime'] = $params['runtime'];
        }

        return $arParams;
    }

    /**
     * Получить дату последнего запроса провайдера
     *
     * @param ProviderInterface $provider
     * @return \DateTime
     */
    public function getProviderLastQueryDate(ProviderInterface $provider)
    {
        $class = HLHelper::initByCode(self::HL_CODE);
        $params = [
            'filter' => [
                'UF_PROVIDER_CODE' => $provider->getCode()
            ]
        ];
        $result = $class::getList($params)->fetch();
        $lastDateUpdate = new \DateTime();

        if($result){
            if($result['UF_LAST_QUERY_TIME'] instanceof DateTime){
                $lastDateUpdate = (new \DateTime())->setTimestamp($result['UF_LAST_QUERY_TIME']->getTimestamp());
            }
        }

        return $lastDateUpdate;
    }

    /**
     * Сохранить дату последнего запроса провайдера
     *
     * @param ProviderInterface $provider
     */
    public function saveProviderLastQueryDate(ProviderInterface $provider)
    {
        $class = HLHelper::initByCode(self::HL_CODE);
        $params = [
            'filter' => [
                'UF_PROVIDER_CODE' => $provider->getCode()
            ]
        ];
        $result = $class::getList($params)->fetch();
        $dateTime = $provider->getLastQueryDate()->format('d.m.Y H:i:s');

        if($result){
            $class::update(
                $result['ID'],
                ['UF_LAST_QUERY_TIME' => $dateTime]
            );
        } else {
            $class::add([
                'UF_LAST_QUERY_TIME' => $dateTime,
                'UF_PROVIDER_CODE' => $provider->getCode()
            ]);
        }
    }
}