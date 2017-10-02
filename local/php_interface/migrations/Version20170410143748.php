<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;
use UW\Helpers\CoreHelper;
use HL\Base as HLHelper;

class Version20170410143748 extends Version {

    protected $description = "HL-блоки провайдеров";
    protected $helper;

    function __construct()
    {
        $this->helper = new HelperManager();
    }

    private function addHLNoticeProviderActivity()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeProviderActivity',
            'TABLE_NAME' => 'exp_notice_provider_activity'
        ]);

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

        $hlEvents = $this->helper->Hlblock()->getHlblock(CoreHelper::HL_CODE_EVENTS);
        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_EVENT_ID',
            [
                'USER_TYPE_ID'      => 'hlblock',
                'SETTINGS'          => [
                    'HLBLOCK_ID' => $hlEvents['ID'],
                    'HLFIELD_ID' => HLHelper::getEnumPropValues($hlEvents, 'UF_NAME')['USER_FIELD_ID']
                ],
                'EDIT_FORM_LABEL'   => ['ru' => 'Событие', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Событие', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Событие', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_USER_ID',
            [
                'USER_TYPE_ID'      => 'integer',
                'EDIT_FORM_LABEL'   => ['ru' => 'ID Пользователя', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'ID Пользователя', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'ID Пользователя', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ITEM_TYPE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Тип элемента', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Тип элемента', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Тип элемента', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ITEM_ID',
            [
                'USER_TYPE_ID'      => 'integer',
                'EDIT_FORM_LABEL'   => ['ru' => 'ID элемента', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'ID элемента', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'ID элемента', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_DATE',
            [
                'USER_TYPE_ID'      => 'datetime',
                'EDIT_FORM_LABEL'   => ['ru' => 'Дата события', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Дата события', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Дата события', 'en' => ''],
            ]
        );
    }

    private function removeHLNoticeProviderActivity()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeProviderActivity');
    }

    private function addHLNoticeProviderNotifications()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeProviderNotifications',
            'TABLE_NAME' => 'exp_notice_provider_notifications'
        ]);

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

        $hlEvents = $this->helper->Hlblock()->getHlblock(CoreHelper::HL_CODE_EVENTS);
        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_EVENT_ID',
            [
                'USER_TYPE_ID'      => 'hlblock',
                'SETTINGS'          => [
                    'HLBLOCK_ID' => $hlEvents['ID'],
                    'HLFIELD_ID' => HLHelper::getEnumPropValues($hlEvents, 'UF_NAME')['USER_FIELD_ID']
                ],
                'EDIT_FORM_LABEL'   => ['ru' => 'Событие', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Событие', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Событие', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ELEMENT_ID',
            [
                'USER_TYPE_ID'      => 'integer',
                'EDIT_FORM_LABEL'   => ['ru' => 'ID элемента', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'ID элемента', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'ID элемента', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_READ',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Прочитанное', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Прочитанное', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Прочитанное', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ELEMENT_TYPE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Тип элемента', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Тип элемента', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Тип элемента', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_DATE',
            [
                'USER_TYPE_ID'      => 'datetime',
                'EDIT_FORM_LABEL'   => ['ru' => 'Дата и время записи', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Дата и время записи', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Дата и время записи', 'en' => ''],
            ]
        );
    }

    private function removeHLNoticeProviderNotifications()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeProviderNotifications');
    }

    private function addHLNoticeProviderNotificationsConsumers()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeProviderNotificationsConsumers',
            'TABLE_NAME' => 'exp_notice_provider_notifications_consumers'
        ]);

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_NOTIFICATION_ID',
            [
                'USER_TYPE_ID'      => 'integer',
                'EDIT_FORM_LABEL'   => ['ru' => 'ID уведомления', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'ID уведомления', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'ID уведомления', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_CONSUMERS_GROUP',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Группа получателей', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Группа получателей', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Группа получателей', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_CONSUMER_ID',
            [
                'USER_TYPE_ID'      => 'integer',
                'EDIT_FORM_LABEL'   => ['ru' => 'ID получателя', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'ID получателя', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'ID получателя', 'en' => ''],
            ]
        );
    }

    private function addForeignKeys()
    {
        $conn = Application::getConnection();
        $conn->queryExecute('ALTER TABLE `exp_notice_provider_notifications_consumers` CHANGE COLUMN `UF_NOTIFICATION_ID` `UF_NOTIFICATION_ID` INT(18) UNSIGNED NULL DEFAULT NULL;');
        $conn->queryExecute('ALTER TABLE `exp_notice_provider_notifications_consumers` ADD INDEX `provider_notification_consumer_event_idx` (`UF_NOTIFICATION_ID` ASC);');
        $conn->queryExecute('ALTER TABLE `exp_notice_provider_notifications_consumers`
            ADD CONSTRAINT `provider_notification_consumer_event`
              FOREIGN KEY (`UF_NOTIFICATION_ID`)
              REFERENCES `exp_notice_provider_notifications` (`ID`)
              ON DELETE CASCADE
              ON UPDATE CASCADE;');
    }

    private function removeHLNoticeProviderNotificationsConsumers()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeProviderNotificationsConsumers');
    }

    public function up(){
        $this->addHLNoticeProviderActivity();
        $this->addHLNoticeProviderNotifications();
        $this->addHLNoticeProviderNotificationsConsumers();
        $this->addForeignKeys();
    }

    public function down(){
        $this->removeHLNoticeProviderActivity();
        $this->removeHLNoticeProviderNotificationsConsumers();
        $this->removeHLNoticeProviderNotifications();

    }

}
