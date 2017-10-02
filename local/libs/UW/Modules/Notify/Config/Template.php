<?php
namespace UW\Modules\Notify\Config;


use HL\Base as HLHelper;
use UW\Modules\Notify\Exceptions\ResourceException;

/**
 * Класс для работы с шаблоном провайдера
 * Class Template
 * @package UW\Modules\Notify\Config
 */
class Template
{
    /**
     * Сохранить шаблон
     * @param $providerId
     * @param $templateData
     * @throws ResourceException
     */
    public function save($providerId, $templateData)
    {
        $hlTemplate = HLHelper::initByCode(ConfigController::HL_SETTINGS_TEMPLATE);

        $fields = [
            'UF_TITLE' => $templateData['title'],
            'UF_BODY' => $templateData['body'],
            'UF_PROVIDER_ID' => $providerId
        ];

        $storedTemplate = $this->get($providerId);

        if(isset($storedTemplate['id']) && intval($storedTemplate['id']) > 0){
            $result = $hlTemplate::update($storedTemplate['id'], $fields);
        } else {
            $result = $hlTemplate::add($fields);
        }

        if(!$result->isSuccess()){
            throw new ResourceException(
                sprintf('Не удалось сохранить шаблон: [%s]', explode(', ', $result->getErrorMessages())),
                ResourceException::DB_CANT_ADD_VALUE
            );
        }
    }

    /**
     * Получить шаблон для провайдера
     *
     * @param int $providerId Идентификатор провайдера
     * @return array
     */
    public function get($providerId)
    {
        $hlTemplate = HLHelper::initByCode(ConfigController::HL_SETTINGS_TEMPLATE);
        $params = [
            'filter' => [
                'UF_PROVIDER_ID' => $providerId
            ]
        ];

        $template = $hlTemplate::getList($params)->fetch();

        if(!$template){
            return [];
        }

        return [
            'id' => $template['ID'],
            'title' => $template['UF_TITLE'],
            'body' => $template['UF_BODY']
        ];
    }
}