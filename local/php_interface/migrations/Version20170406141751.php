<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;
use HL\Base as HLHelper;

class Version20170406141751 extends Version
{

    protected $description = "Хайлоады с конфигурацией модуля Уведомления";
    protected $helper;

    function __construct()
    {
        $this->helper = new HelperManager();
    }

    private function getData($hlName)
    {
        $fileData = file_get_contents(__DIR__ . '/storage/'.$hlName.'.json');
        return json_decode($fileData, true);
    }

    private function importData($hlName, $data)
    {
        $hlEvents = HLHelper::initByCode($hlName);

        foreach ($data as $row){

            $hlEvents::add($row);
        }
    }

    private function addHLNoticeSettingsEE()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeSettingsEE',
            'TABLE_NAME' => 'exp_notice_settings_ee'
        ]);

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_EVENT_CODE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Код события', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Код события', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Код события', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ENTITY_TYPE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Тип сущности', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Тип сущности', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Тип сущности', 'en' => ''],
            ]
        );
    }

    private function removeHLNoticeSettingsEE()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeSettingsEE');
    }

    private function addHLNoticeSettingsProvider()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeSettingsProvider',
            'TABLE_NAME' => 'exp_notice_settings_provider'
        ]);

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_EE_ID',
            [
                'USER_TYPE_ID'      => 'integer',
                'EDIT_FORM_LABEL'   => ['ru' => 'Сущность-событие ID', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Сущность-событие ID', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Сущность-событие ID', 'en' => ''],
            ]
        );

        $propCodeId = $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_CODE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Код провайдера', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Код провайдера', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Код провайдера', 'en' => ''],
            ]
        );

        $this->saveData('ProviderHLId', $hlId);
        $this->saveData('ProviderFieldCodeId', $propCodeId);
    }

    private function removeHLNoticeSettingsProvider()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeSettingsProvider');
    }

    private function addHLNoticeSettingsRule()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeSettingsRule',
            'TABLE_NAME' => 'exp_notice_settings_rule'
        ]);

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ASSERTIONS_LOGIC',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Логика для утверждений', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Логика для утверждений', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Логика для утверждений', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_CONSUMERS',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Получатели', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Получатели', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Получатели', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ASSERTIONS',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Утверждения', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Утверждения', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Утверждения', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_PROVIDER_ID',
            [
                'USER_TYPE_ID'      => 'hlblock',
                'SETTINGS'          => [
                    'HLBLOCK_ID' => $this->getSavedData('ProviderHLId'),
                    'HLFIELD_ID' => $this->getSavedData('ProviderFieldCodeId')
                ],
                'EDIT_FORM_LABEL'   => ['ru' => 'Провайдер', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Провайдер', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Провайдер', 'en' => ''],
            ]
        );
    }

    private function removeHLNoticeSettingsRule()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeSettingsRule');
    }

    private function addHLNoticeSettingsTemplate()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeSettingsTemplate',
            'TABLE_NAME' => 'exp_notice_settings_template'
        ]);

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_PROVIDER_ID',
            [
                'USER_TYPE_ID'      => 'hlblock',
                'SETTINGS'          => [
                    'HLBLOCK_ID' => $this->getSavedData('ProviderHLId'),
                    'HLFIELD_ID' => $this->getSavedData('ProviderFieldCodeId')
                ],
                'EDIT_FORM_LABEL'   => ['ru' => 'Провайдер', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Провайдер', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Провайдер', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_BODY',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Тело', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Тело', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Тело', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_TITLE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Заголовок', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Заголовок', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Заголовок', 'en' => ''],
            ]
        );
    }

    private function removeHLNoticeSettingsTemplate()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeSettingsTemplate');
    }

    private function addForeignKeys()
    {
        $conn = Application::getConnection();
        $conn->queryExecute('ALTER TABLE `exp_notice_settings_provider` CHANGE COLUMN `UF_EE_ID` `UF_EE_ID` INT(18) UNSIGNED NULL DEFAULT NULL;');
        $conn->queryExecute('ALTER TABLE `exp_notice_settings_provider` ADD INDEX `notice_provider_event_idx` (`UF_EE_ID` ASC);');
        $conn->queryExecute('ALTER TABLE `exp_notice_settings_provider`
            ADD CONSTRAINT `notice_provider_event`
              FOREIGN KEY (`UF_EE_ID`)
              REFERENCES `exp_notice_settings_ee` (`ID`)
              ON DELETE CASCADE
              ON UPDATE CASCADE;');

        $conn->queryExecute('ALTER TABLE `exp_notice_settings_rule` CHANGE COLUMN `UF_PROVIDER_ID` `UF_PROVIDER_ID` INT(18) ZEROFILL NULL DEFAULT NULL ;');
        $conn->queryExecute('ALTER TABLE `exp_notice_settings_rule` ADD INDEX `notice_rule_provider_idx` (`UF_PROVIDER_ID` ASC);');
        $conn->queryExecute('ALTER TABLE `exp_notice_settings_rule` 
            ADD CONSTRAINT `notice_rule_provider`
              FOREIGN KEY (`UF_PROVIDER_ID`)
              REFERENCES `exp_notice_settings_provider` (`ID`)
              ON DELETE CASCADE
              ON UPDATE CASCADE;');

        $conn->queryExecute('ALTER TABLE `exp_notice_settings_template` CHANGE COLUMN `UF_PROVIDER_ID` `UF_PROVIDER_ID` INT(18) UNSIGNED NULL DEFAULT NULL ;');
        $conn->queryExecute('ALTER TABLE `exp_notice_settings_template` ADD INDEX `notify_template_provider_idx` (`UF_PROVIDER_ID` ASC);');
        $conn->queryExecute('ALTER TABLE `exp_notice_settings_template`
            ADD CONSTRAINT `notify_template_provider`
              FOREIGN KEY (`UF_PROVIDER_ID`)
              REFERENCES `exp_notice_settings_provider` (`ID`)
              ON DELETE CASCADE
              ON UPDATE CASCADE;');

    }

    public function up()
    {
        $this->addHLNoticeSettingsEE();
        $this->importData('NoticeSettingsEE', $this->getData('NoticeSettingsEE'));
        $this->addHLNoticeSettingsProvider();
        $this->importData('NoticeSettingsProvider', $this->getData('NoticeSettingsProvider'));
        $this->addHLNoticeSettingsRule();
        $this->importData('NoticeSettingsRule', $this->getData('NoticeSettingsRule'));
        $this->addHLNoticeSettingsTemplate();
        $this->importData('NoticeSettingsTemplate', $this->getData('NoticeSettingsTemplate'));
        $this->addForeignKeys();

        $this->deleteSavedData();
    }

    public function down()
    {
        $this->removeHLNoticeSettingsTemplate();
        $this->removeHLNoticeSettingsRule();
        $this->removeHLNoticeSettingsProvider();
        $this->removeHLNoticeSettingsEE();



    }

}
