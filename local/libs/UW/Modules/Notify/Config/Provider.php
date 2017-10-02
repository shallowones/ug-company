<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 27.03.2017
 * Time: 11:42
 */

namespace UW\Modules\Notify\Config;


use HL\Base as HLHelper;
use UW\Modules\Notify\Exceptions\ConfigException;
use UW\Modules\Notify\Exceptions\ResourceException;
use UW\Modules\Notify\Providers\ProviderFactory;
use UW\Modules\Notify\Providers\ProviderInterface;

/**
 * Класс для работы с провайдером уведомлений
 *
 * Class Provider
 * @package UW\Modules\Notify\Config
 */
class Provider
{
    /**
     * Получить список зарегистрированных провайдеров
     *
     * @param $eeId
     * @return array
     */
    private function getRegisteredProviders($eeId)
    {
        $hlProvider = HLHelper::initByCode(ConfigController::HL_SETTINGS_PROVIDER);

        $params = [
            'filter' => [
                'UF_EE_ID' => $eeId
            ]
        ];

        $rsUsedProviders = $hlProvider::getList($params);
        $arUsedProviderCodes = [];

        while ($tmpArr = $rsUsedProviders->fetch()){
            $arUsedProviderCodes[] = [
                'id' => $tmpArr['ID'],
                'code' => $tmpArr['UF_CODE'],
            ];
        }

        return $arUsedProviderCodes;
    }

    /**
     * Добавить провайдер
     *
     * @param int $eeId Идентификатор связи событие-сущность
     * @param string $providerCode Код провайдера
     * @return int
     * @throws ConfigException
     * @throws ResourceException
     */
    public function add($eeId, $providerCode)
    {
        /*$arProviders = $this->getList($eeId);
        $providerForSave = current(array_filter(
            $arProviders,
            function($value) use ($providerCode) {
                return $value['code'] == $providerCode && empty($value['id']);
            }
        ));

        if(!$providerForSave){
            throw new ConfigException(
                'Нельзя добавить указанный провайдер', ConfigException::PROVIDER_UNACCEPTABLE_REGISTER
            );
        }*/

        $hlProvider = HLHelper::initByCode(ConfigController::HL_SETTINGS_PROVIDER);

        $fields = [
            'UF_EE_ID' => $eeId,
            'UF_CODE' => $providerCode
        ];

        $result = $hlProvider::add($fields);

        if(!$result->isSuccess()){
            throw new ResourceException(
                sprintf('Не удалось добавить провайдер: [%s]', explode(', ', $result->getErrorMessages())),
                ResourceException::DB_CANT_ADD_VALUE
            );
        }

        return $result->getId();
    }

    /**
     * Сохранить данные провайдера
     *
     * @param $providerId
     * @param $data
     * @throws ResourceException
     */
    public function save($providerId, $data)
    {
        $hlProvider = HLHelper::initByCode(ConfigController::HL_SETTINGS_PROVIDER);
        $fields = [
            'UF_TPL_ALG_CODE' => $data['templateAlgorithm'],
            'UF_CONSUMER_ALG_CODE' => $data['consumersAlgorithm']
        ];

        $result = $hlProvider::update($providerId, $fields);

        if(!$result->isSuccess()){
            throw new ResourceException(
                sprintf('Не удалось обновить провайдер: [%s]', explode(', ', $result->getErrorMessages())),
                ResourceException::DB_CANT_ADD_VALUE
            );
        }
    }

    /**
     * Удалить провайдера
     * @param $providerId
     * @throws ResourceException
     */
    public function delete($providerId)
    {
        $hlProvider = HLHelper::initByCode(ConfigController::HL_SETTINGS_PROVIDER);
        $result = $hlProvider::delete($providerId);

        if(!$result->isSuccess()){
            throw new ResourceException(
                sprintf('Не удалось удалить провайдер: [%s]', explode(', ', $result->getErrorMessages())),
                ResourceException::DB_CANT_ADD_VALUE
            );
        }
    }

    /**
     * Получить список зарегистрированных провайдеров
     *
     * @param int $eeId Идентификатор связи событие-сущность
     * @return array
     */
    public function getList($eeId)
    {
        $arUsedProviders = $this->getRegisteredProviders($eeId);
        $arTotalProviders = (new ProviderFactory())->buildAll();

        $result = [];

        foreach ($arTotalProviders as $provider){
            /**
             * @var ProviderInterface $provider
             */
            $result[] = [
                'name' => $provider->getName(),
                'code' => $provider->getCode()
            ];
        }

        foreach ($arUsedProviders as $usedProvider){
            foreach ($arTotalProviders as $provider){
                /**
                 * @var ProviderInterface $provider
                 */
                if($usedProvider['code'] == $provider->getCode()){
                    $usedProvider['name'] = $provider->getName();

                    break;
                }
            }

            $result[] = [
                'id' => intval($usedProvider['id']),
                'name' => $usedProvider['name'],
                'code' => $usedProvider['code']
            ];
        }

        return $result;
    }

    /**
     * Получить детальную инфмаорцию по провайдеру
     *
     * @param $providerId
     * @return array
     * @throws ConfigException
     */
    public function getDetail($providerId)
    {
        $template = new Template();
        $rule = new Rule();
        $hlProvider = HLHelper::initByCode(ConfigController::HL_SETTINGS_PROVIDER);
        $arProvider = $hlProvider::getById($providerId)->fetch();
        $obProvider = (new ProviderFactory())->buildByCode($arProvider['UF_CODE']);

        if(!$arProvider){
            throw new ConfigException(
                'Не найден провайдер с указанным идентификатором',
                ConfigException::PROVIDER_NOT_FOUND
            );
        }

        $result = [
            'id' => intval($providerId),
            'code' => $obProvider->getCode(),
            'name' => $obProvider->getName(),
            'template' => [
                'title' => '',
                'body' => ''
            ],
            'rules' => []
        ];

        if($arTemplate = $template->get($providerId)){
            $result['template']['title'] = $arTemplate['title'];
            $result['template']['body'] = $arTemplate['body'];
        }

        if($arRules = $rule->getList($providerId)){
            foreach ($arRules as $rule){
                $result['rules'][] = [
                    'id' => intval($rule['id']),
                    'assertions' => $rule['assertions'],
                    'logic' => $rule['logic'],
                    'consumers' => $rule['consumers'],
                ];
            }
        }

        return $result;
    }

}