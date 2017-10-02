<?php

/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 28.06.2017
 * Time: 14:26
 */
class RequisitesComponent extends CBitrixComponent
{
    public function onPrepareComponentParams($params)
    {
        $params['HL_CODE'] = trim($params['HL_CODE']);
        if (empty($params['HL_CODE'])) {
            $params['HL_CODE'] = 'OrganizationRequisites';
        }

        // Убираем пустые элементы
        foreach ($params["GROUPS_FOR_SELECT_LIST"] as $key => $val) {
            if (!$val) {
                unset($params["GROUPS_FOR_SELECT_LIST"][$key]);
            }
        }

        if (!$params['GROUP_MODERATORS']) {
            $params['GROUP_MODERATORS'] = 'MODERATORS';
        }
        if (!$params['ORGANIZATION_FIELD_CODE']) {
            $params['ORGANIZATION_FIELD_CODE'] = 'UF_ORG_ID';
        }

        return $params;
    }

    /**
     * Возвращает ID организации
     * @return int
     */
    public function getOrganizationID()
    {
        global $USER;
        $orgID = 0;

        $rsUser = CUser::GetList(
            $by1 = 'asc', $order1 = 'desc',
            [
                'ID' => $USER->GetID()
            ],
            [
                'FIELDS' => ['ID'],
                'SELECT' => [$this->arParams['ORGANIZATION_FIELD_CODE']]
            ]
        );
        if ($arUser = $rsUser->Fetch()) {
            if (array_key_exists($this->arParams['ORGANIZATION_FIELD_CODE'], $arUser)) {
                $orgID = $arUser[$this->arParams['ORGANIZATION_FIELD_CODE']];
            }
        }

        return $orgID;
    }

    /**
     * Возвращает группы текущего пользователя
     * @param int $userID
     * @return array
     */
    public function getGroupsCurrentUser($userID = 0)
    {
        global $USER;
        $groupsCurrentUser = [];

        if ($userID === 0) {
            $userID = $USER->GetID();
        }

        $params = [
            'select' => [
                'STRING_ID' => 'GROUP.STRING_ID',
                'GROUP_ID'
            ],
            'filter' => [
                'USER_ID' => $userID
            ]
        ];
        $rsGetUserGroupList = \Bitrix\Main\UserGroupTable::getList($params);
        while ($arGetUserGroupList = $rsGetUserGroupList->fetch()) {
            $groupsCurrentUser[$arGetUserGroupList['STRING_ID']] = $arGetUserGroupList['GROUP_ID'];
        }

        return $groupsCurrentUser;
    }

    /**
     * Проверка прав Модератора или админа
     * @param $groupsCurrentUser
     * @return bool
     */
    public function checkAllowModerator($groupsCurrentUser)
    {
        global $USER;
        if (
            $USER->IsAdmin() ||
            array_key_exists($this->arParams['GROUP_MODERATORS'], $groupsCurrentUser)
        ) {
            $this->arResult['IS_MODERATOR'] = 'Y';
            foreach ($this->arParams['GROUPS_FOR_SELECT_LIST'] as $value) {
                if ($value) {
                    return $value;
                }
            }
        }

        return false;
    }

    /**
     * Проверка прав пользователя
     * @param $groupsCurrentUser
     * @return bool
     */
    public function checkAllowUser($groupsCurrentUser)
    {
        // Бежим по группам, которым разрешен доступ
        foreach ($this->arParams['GROUPS_FOR_SELECT_LIST'] as $codeGroup) {
            // если хоть одна их этих групп есть в группах текущего пользователя, значит гут
            if (array_key_exists($codeGroup, $groupsCurrentUser)) {
                return $codeGroup;
            }
        }

        return false;
    }

    /**
     * Маршрутизация
     * @return string
     */
    protected function routers()
    {
        global $APPLICATION;
        $componentPage = '';
        $arDefaultUrlTemplates404 = array(
            "nf_404" => "",
            "requisites" => ""
        );
        $arDefaultVariableAliases404 = array();
        $arComponentVariables = array();

        if ($this->arParams["SEF_MODE"] === "Y") {
            $arVariables = array();

            $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates($arDefaultUrlTemplates404, $this->arParams["SEF_URL_TEMPLATES"]);
            $arVariableAliases = CComponentEngine::MakeComponentVariableAliases($arDefaultVariableAliases404, $this->arParams["VARIABLE_ALIASES"]);

            $engine = new CComponentEngine($this);

            $componentPage = $engine->guessComponentPath(
                $this->arParams["SEF_FOLDER"],
                $arUrlTemplates,
                $arVariables
            );

            $b404 = false;
            if (!$componentPage || $componentPage === 'nf_404') {
                $componentPage = "requisites";
                $b404 = true;
            }

            if ($b404 && $this->arParams["SET_STATUS_404"] === "Y") {
                $folder404 = str_replace("\\", "/", $this->arParams["SEF_FOLDER"]);
                if ($folder404 != "/")
                    $folder404 = "/" . trim($folder404, "/ \t\n\r\0\x0B") . "/";
                if (substr($folder404, -1) === "/")
                    $folder404 .= "index.php";

                if ($folder404 != $APPLICATION->GetCurPage(true))
                    CHTTP::SetStatus("404 Not Found");
            }

            CComponentEngine::InitComponentVariables($componentPage, $arComponentVariables, $arVariableAliases, $arVariables);

            $this->arResult = array(
                "FOLDER" => $this->arParams["SEF_FOLDER"],
                "URL_TEMPLATES" => $arUrlTemplates,
                "VARIABLES" => $arVariables,
                "ALIASES" => $arVariableAliases,
            );
        }

        return $componentPage;
    }

    /**
     * Возвращает контролы для группы
     * @param $groupName
     */
    public function getGroupControls($groupName)
    {
        $controls = [
            'INDIVIDUALS' => [
            ],
            'ENTREPRENEUR' => [
            ],
            'ORGANIZATION' => [
                'UF_ORG_NAME',
                'UF_ORG_INN',
                'UF_ORG_OGRN',
                'UF_UR_ADDRESS',
                'UF_FACT_ADDRESS',
                'UF_BANK_NAME',
                'UF_ORG_RS',
                'UF_ORG_KS',
                'UF_ORG_BIK',
                'UF_ORG_KPP',
                'UF_ORG_PHONE',
            ]
        ];

        $steps = [
            'INDIVIDUALS' => [
                'step-1' => [
                    'id' => 'step-1',
                    'label' => 'Step 1',
                    'controls' => []
                ]
            ],
            'ENTREPRENEUR' => [
                'step-1' => [
                    'id' => 'step-1',
                    'label' => 'Step 1',
                    'controls' => []
                ]
            ],
            'ORGANIZATION' => [
                'step-1' => [
                    'id' => 'step-1',
                    'label' => 'Step 1',
                    'controls' => [
                        'UF_ORG_NAME',
                        'UF_ORG_INN',
                        'UF_ORG_OGRN',
                        'UF_UR_ADDRESS',
                        'UF_FACT_ADDRESS',
                        'UF_BANK_NAME',
                        'UF_ORG_RS',
                        'UF_ORG_KS',
                        'UF_ORG_BIK',
                        'UF_ORG_KPP',
                        'UF_ORG_PHONE',
                    ]
                ]
            ]
        ];

        $this->arResult['HL_FIELDS'] = $controls[$groupName];
        $this->arResult['STEPS'] = $steps[$groupName];
    }

    public function executeComponent()
    {
        $componentPage = $this->routers();

        // Проверка на права
        $groupsCurrentUser = $this->getGroupsCurrentUser();
        if (!$this->checkAllowUser($groupsCurrentUser) && !$this->checkAllowModerator($groupsCurrentUser)) {
            ShowError('У вас нет прав.');
            return;
        }

        $this->includeComponentTemplate($componentPage);
    }
}