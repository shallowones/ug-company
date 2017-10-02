<?php

namespace Sprint\Migration;


class Version20170410143832 extends Version {

    protected $description = "HL-блоки модуля - настройки провайдеров, лог событий и т.д.";
    protected $helper;

    function __construct()
    {
        $this->helper = new HelperManager();
    }

    private function addHLNoticeEventsLog()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeEventsLog',
            'TABLE_NAME' => 'exp_notice_events_log'
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
            'UF_ENTITY_ID',
            [
                'USER_TYPE_ID'      => 'integer',
                'EDIT_FORM_LABEL'   => ['ru' => 'Идентификатор сущности', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Идентификатор сущности', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Идентификатор сущности', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_ENTITY_CODE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Код сущности', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Код сущности', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Код сущности', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_DATA',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Данные', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Данные', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Данные', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_DATE_TIME',
            [
                'USER_TYPE_ID'      => 'datetime',
                'EDIT_FORM_LABEL'   => ['ru' => 'Дата и время события', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Дата и время события', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Дата и время события', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_INITIATOR_ID',
            [
                'USER_TYPE_ID'      => 'integer',
                'EDIT_FORM_LABEL'   => ['ru' => 'Инициатор события', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Инициатор события', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Инициатор события', 'en' => ''],
            ]
        );
    }

    private function removeHLNoticeEventsLog()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeEventsLog');
    }

    private function addHLNoticeProvidersData()
    {
        $hlId = $this->helper->Hlblock()->addHlblockIfNotExists([
            'NAME'       => 'NoticeProvidersData',
            'TABLE_NAME' => 'notice_providers_data'
        ]);

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_LAST_QUERY_TIME',
            [
                'USER_TYPE_ID'      => 'datetime',
                'EDIT_FORM_LABEL'   => ['ru' => 'Время последнего запроса', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Время последнего запроса', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Время последнего запроса', 'en' => ''],
            ]
        );

        $this->helper->UserTypeEntity()->addUserTypeEntityIfNotExists(
            'HLBLOCK_' . $hlId,
            'UF_PROVIDER_CODE',
            [
                'USER_TYPE_ID'      => 'string',
                'EDIT_FORM_LABEL'   => ['ru' => 'Код провайдера', 'en' => ''],
                'LIST_COLUMN_LABEL' => ['ru' => 'Код провайдера', 'en' => ''],
                'LIST_FILTER_LABEL' => ['ru' => 'Код провайдера', 'en' => ''],
            ]
        );
    }

    private function removeHLNoticeProvidersData()
    {
        $this->helper->Hlblock()->deleteHlblockIfExists('NoticeProvidersData');
    }

    public function up(){
        $this->addHLNoticeEventsLog();
        $this->addHLNoticeProvidersData();
    }

    public function down(){
        $this->removeHLNoticeEventsLog();
        $this->removeHLNoticeProvidersData();
    }

}
