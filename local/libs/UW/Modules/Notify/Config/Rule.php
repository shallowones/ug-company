<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 27.03.2017
 * Time: 11:46
 */

namespace UW\Modules\Notify\Config;


use HL\Base as HLHelper;
use UW\Modules\Notify\Exceptions\ResourceException;

class Rule
{
    /**
     * Сохранить правило
     *
     * @param int $providerId Идентификатор провайдера
     * @param array $ruleData Данные правила
     * @param int $ruleId Идентификатор правила
     * @throws ResourceException
     */
    public function save($providerId, $ruleData, $ruleId = 0)
    {
        $hlRule = HLHelper::initByCode(ConfigController::HL_SETTINGS_RULE);

        if(!isset($ruleData['assertions']) || !is_array($ruleData['assertions'])){
            $ruleData['assertions'] = [];
        }

        if(!isset($ruleData['consumers']) || !is_array($ruleData['consumers'])){
            $ruleData['consumers'] = [];
        }

        $fields = [
            'UF_ASSERTIONS'       => json_encode($ruleData['assertions']),
            'UF_CONSUMERS'        => json_encode($ruleData['consumers']),
            'UF_ASSERTIONS_LOGIC' => $ruleData['logic'],
            'UF_PROVIDER_ID'      => $providerId
        ];

        if (intval($ruleId) > 0) {
            $result = $hlRule::update($ruleId, $fields);
        } else {
            $result = $hlRule::add($fields);
        }

        if (!$result->isSuccess()) {
            throw new ResourceException(
                sprintf('Не удалось сохранить правило, ID %d: [%s]', $ruleId, explode(', ', $result->getErrorMessages())),
                ResourceException::DB_CANT_ADD_VALUE
            );
        }
    }

    /**
     * Удалить правило
     *
     * @param int $ruleId Идентификатор правила
     * @throws ResourceException
     */
    public function delete($ruleId)
    {
        $hlRule = HLHelper::initByCode(ConfigController::HL_SETTINGS_RULE);

        $result = $hlRule::delete($ruleId);

        if (!$result->isSuccess()) {
            throw new ResourceException(
                sprintf('Не удалось удалить правило: [%s]', explode(', ', $result->getErrorMessages())),
                ResourceException::DB_CANT_DELETE_VALUE
            );
        }
    }

    /**
     * Получить список правил для провайдера
     *
     * @param int $providerId Идентификатор провайдера
     * @return array
     */
    public function getList($providerId)
    {
        $hlRule = HLHelper::initByCode(ConfigController::HL_SETTINGS_RULE);

        $params = [
            'filter' => [
                'UF_PROVIDER_ID' => $providerId
            ]
        ];

        $result = [];
        $rsRules = $hlRule::getList($params);

        while ($tmpArr = $rsRules->fetch()) {
            $result[] = [
                'id'         => $tmpArr['ID'],
                'logic'      => ('or' == $tmpArr['UF_ASSERTIONS_LOGIC']) ? $tmpArr['UF_ASSERTIONS_LOGIC'] : 'and',
                'assertions' => (trim($tmpArr['UF_ASSERTIONS']) == false) ? [] : json_decode($tmpArr['UF_ASSERTIONS']),
                'consumers'  => (trim($tmpArr['UF_CONSUMERS']) == false) ? [] : json_decode($tmpArr['UF_CONSUMERS'])
            ];
        }

        return $result;
    }
}