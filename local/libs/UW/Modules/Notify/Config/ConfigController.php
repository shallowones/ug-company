<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 20.02.2017
 * Time: 15:49
 */

namespace UW\Modules\Notify\Config;


use Bitrix\Main\Entity\ReferenceField;
use UW\Config;
use UW\Facades\MainFacade;
use HL\Base as HLHelper;
use UW\Logger;
use UW\Modules\Notify\Config\Rule as ConfigRule;
use UW\Modules\Notify\Exceptions\ConfigException;
use UW\Modules\Notify\ProviderConfigData;
use UW\Modules\Notify\Specifications\AndISpecificationSatisfied;
use UW\Modules\Notify\Specifications\OrISpecificationSatisfied;
use UW\Modules\Notify\Specifications\SpecificationFactory;


/**
 * Класс обеспечивающий доступ к конфигурации модуля уведомлений
 *
 * Используется при формировании набора сообщений (для отправки) каждым провайдером
 *
 * Class Configuration
 * @package UW\Modules\Notify
 */
class ConfigController
{
    /**
     * HL-блок связывающий Сущность, Событие и Провайдера
     */
    const HL_SETTINGS_EE = 'NoticeSettingsEE';
    /**
     * HL-блок с Провайдерами
     */
    const HL_SETTINGS_PROVIDER = 'NoticeSettingsProvider';
    /**
     * HL-блок с Правилами
     */
    const HL_SETTINGS_RULE = 'NoticeSettingsRule';
    /**
     * HL-блок с Шаблонами
     */
    const HL_SETTINGS_TEMPLATE = 'NoticeSettingsTemplate';

    protected $coreMainFacade;
    protected $config;

    function __construct()
    {
        $this->coreMainFacade = new MainFacade();
        $this->config = new Config();
    }

    /**
     * Получить коллекцию объектов "Конфигурация данных провайдера"
     *
     * @param $providerCode
     * @return ProviderConfigData[]
     */
    public function getProviderDataCollection($providerCode)
    {
        $hlProvider = HLHelper::initByCode(self::HL_SETTINGS_PROVIDER);
        $hlTemplate = HLHelper::initByCode(self::HL_SETTINGS_TEMPLATE);
        $hlEE = HLHelper::initByCode(self::HL_SETTINGS_EE);
        $configRule = new ConfigRule();

        $params = [
            'select' => [
                'entityCode' => 'EE.UF_ENTITY_TYPE',
                'eventCode' => 'EE.UF_EVENT_CODE',
                'providerCode' => 'UF_CODE',
                'providerId' => 'ID',
                'templateBody' => 'Template.UF_BODY',
                'templateTitle' => 'Template.UF_TITLE'
            ],
            'filter' => [
                'UF_CODE' => $providerCode
            ],
            'runtime' => [
                new ReferenceField(
                    'EE',
                    $hlEE,
                    ['this.UF_EE_ID' => 'ref.ID']
                ),
                new ReferenceField(
                    'Template',
                    $hlTemplate,
                    ['this.ID' => 'ref.UF_PROVIDER_ID']
                )
            ]
        ];

        $rsProviders = $hlProvider::getList($params);
        $result = [];

        while ($tmpArr = $rsProviders->fetch()){
            Logger::trace(sprintf('Создание КДП, id: %d', $tmpArr['providerId']));
            $tmpArr['rules'] = $configRule->getList($tmpArr['providerId']);

            $arRules = [];

            Logger::trace('Построение списка правил');
            foreach ($tmpArr['rules'] as $rule){
                $arSpecifications = [];
                foreach ($rule['assertions'] as $specification) {
                    $arSpecifications[] = SpecificationFactory::create($specification, $tmpArr['entityCode']);
                }

                if(!isset($rule['consumers']) || !is_array($rule['consumers'])){
                    Logger::warn(sprintf(
                        'Данные о получателях отсутствуют или не явлются массивом - %s',
                        serialize($rule['consumers'])
                    ));

                    $rule['consumers'] = [];
                }

                $arRules[] = \UW\Modules\Notify\Rule::build($arSpecifications, $rule['logic'], $rule['consumers']);
            }

            Logger::trace('Построение шаблона');
            $template = new \UW\Modules\Notify\Template($tmpArr['templateTitle'], $tmpArr['templateBody']);

            Logger::trace('Создание объекта КДП');

            try{
                $result[] = new ProviderConfigData(
                    $tmpArr['providerCode'],
                    $tmpArr['eventCode'],
                    $tmpArr['entityCode'],
                    $template,
                    $arRules
                );
            } catch (\Exception $e) {
                Logger::trace('Не удалось создать объект КДП');
                Logger::fatal('При создании объекта КДП  произошла ошибка', $e);

                continue;
            }

            Logger::trace('Создание объекта КДП успешно завершено');
        }

        return $result;
    }

    /**
     * Получить коды всех событий, которые прослушивает модуль
     *
     * @return array
     */
    public function getEventsList()
    {
        $result = [];

        try{
            $hlEvent = HLHelper::initByCode(self::HL_SETTINGS_EE);

            $params = [
                'select' => ['UF_EVENT_CODE']
            ];

            $rsEvents = $hlEvent::getList($params);


            while ($event = $rsEvents->fetch()){
                $result[] = $event['UF_EVENT_CODE'];
            }
        } catch (\Exception $e){
            Logger::fatal('Произошла ошибка при получении списка событий для регистрации хендлеров модуля', $e);
        }

        return $result;
    }
}