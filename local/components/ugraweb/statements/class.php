<?

class StatementComponent extends CBitrixComponent
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
            "list" => "",
            "detail" => "#ELEMENT_ID#/",
            "add" => "add/",
            "edit" => "#ELEMENT_ID#/edit/",
            "status" => "#ELEMENT_ID#/status/",
            "drafts" => "drafts/",
        );
        $arDefaultVariableAliases404 = array();
        $arComponentVariables = array(
            "ELEMENT_ID"
        );

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
                $componentPage = "list";
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
        $controlsAll = [
            'INDIVIDUALS' => [
                'UF_STATUS',
                'UF_VIEW_REQUEST_SUB',
                'UF_TYPE_GET_DOCUMENT',
                'UF_LIST_OFFICE',
                'UF_FIO',
                'UF_BIRTHDAY',
                'UF_PASSPORT_SERIES',
                'UF_PASSPORT_CODE',
                'UF_PASSPORT_DATE',
                'UF_PASSPORT_ISSUED',
                'UF_SNILS',
                'UF_POSTCODE',
                'UF_REGION',
                'UF_AREA',
                'UF_CITY',
                'UF_STREET',
                'UF_HOME',
                'UF_HOUSING',
                'UF_BUILDING',
                'UF_APARTMENT',
                'UF_CONTACT_PERSONAL',
                'UF_OBJECT_NAME',
                'UF_OBJECT',
                'UF_OBJECT_AREA',
                'UF_OBJECT_CITY',
                'UF_OBJECT_STREET',
                'UF_OBJECT_HOME',
                'UF_OBJECT_HOUSING',
                'UF_OBJECT_BUILDING',
                'UF_OBJECT_APARTMENT',
                'UF_INVENTORY_NUMBER',
                //'UF_MAX_POWER',
                //'UF_LEVEL_VOLTAGE',
                //'UF_CATEGORY_SECURITY',
                'UF_CNT_POINT_CONNECT',
                //'UF_VAL_TECH_ARMOR',
                //'UF_VAL_CRASH_ARMOR',
                //'UF_RATIONALE_ARMOR',
                'UF_FILE_PLAN',
                'UF_FILES_EXT',
                'UF_FILE_SCAN_PRAVO',
                'UF_FILE_PROTOCOL',
                'UF_FILE_DOVERENOST',

            ],
            'ENTREPRENEUR' => [
                'UF_STATUS',
                'UF_VIEW_REQUEST_SUB',
                'UF_TYPE_GET_DOCUMENT',
                'UF_LIST_OFFICE',
                'UF_FIO',
                'UF_BIRTHDAY',
                'UF_PASSPORT_SERIES',
                'UF_PASSPORT_CODE',
                'UF_PASSPORT_DATE',
                'UF_PASSPORT_ISSUED',
                'UF_SNILS',
                'UF_POSTCODE',
                'UF_REGION',
                'UF_AREA',
                'UF_CITY',
                'UF_STREET',
                'UF_HOME',
                'UF_HOUSING',
                'UF_BUILDING',
                'UF_APARTMENT',
                'UF_CONTACT_PERSONAL',
                'UF_OBJECT_NAME',
                'UF_OBJECT',
                'UF_OBJECT_AREA',
                'UF_OBJECT_CITY',
                'UF_OBJECT_STREET',
                'UF_OBJECT_HOME',
                'UF_OBJECT_HOUSING',
                'UF_OBJECT_BUILDING',
                'UF_OBJECT_APARTMENT',
                'UF_INVENTORY_NUMBER',
                //'UF_MAX_POWER',
                //'UF_LEVEL_VOLTAGE',
                //'UF_CATEGORY_SECURITY',
                'UF_CNT_POINT_CONNECT',
                //'UF_VAL_TECH_ARMOR',
                //'UF_VAL_CRASH_ARMOR',
                //'UF_RATIONALE_ARMOR',
                'UF_FILE_PLAN',
                'UF_FILES_EXT',
                'UF_FILE_SCAN_PRAVO',
                'UF_FILE_PROTOCOL',
                'UF_FILE_DOVERENOST',
            ],
            'ORGANIZATION' => [
                'UF_STATUS',
                'UF_VIEW_REQUEST_SUB',
                'UF_TYPE_GET_DOCUMENT',
                'UF_LIST_OFFICE',
                'UF_ORG_NAME',
                'UF_ORG_UR_ADDRESS',
                'UF_ORG_ADDRESS',
                'UF_ORG_N_EGRUL',
                'UF_ORG_DATE_EGRUL',
                'UF_ORG_INN',
                'UF_ORG_KPP',
                'UF_ORG_BANK',
                'UF_ORG_RS',
                'UF_ORG_KS',
                'UF_ORG_BIK',
                'UF_ORG_OKPO',
                'UF_ORG_OGRN',
                'UF_PHONE',
                'UF_ORG_OKVED',
                'UF_CONTACT_PERSONAL',
                'UF_OBJECT_NAME',
                'UF_OBJECT',
                'UF_OBJECT_AREA',
                'UF_OBJECT_CITY',
                'UF_OBJECT_STREET',
                'UF_OBJECT_HOME',
                'UF_OBJECT_HOUSING',
                'UF_OBJECT_BUILDING',
                'UF_OBJECT_APARTMENT',
                'UF_INVENTORY_NUMBER',
                //'UF_MAX_POWER',
                //'UF_LEVEL_VOLTAGE',
                //'UF_CATEGORY_SECURITY',
                'UF_CNT_POINT_CONNECT',
                //'UF_VAL_TECH_ARMOR',
                //'UF_VAL_CRASH_ARMOR',
                //'UF_RATIONALE_ARMOR',
                'UF_FILE_PLAN',
                'UF_FILES_EXT',
                'UF_FILE_SCAN_PRAVO',
                'UF_FILE_PROTOCOL',
                'UF_FILE_DOVERENOST',
            ]
        ];

        $stepsAll = [
            'INDIVIDUALS' => [
                'step-1' => [
                    'id' => 'step-1',
                    'label' => 'Шаг 1. Общие сведения',
                    'controls' => ['UF_VIEW_REQUEST_SUB', 'UF_TYPE_GET_DOCUMENT', 'UF_LIST_OFFICE']
                ],
                'step-2' => [
                    'id' => 'step-2',
                    'label' => 'Шаг 2. Реквизиты заявителя',
                    'controls' => [
                        'UF_FIO',
                        'UF_BIRTHDAY',
                        'UF_PASSPORT_SERIES',
                        'UF_PASSPORT_CODE',
                        'UF_PASSPORT_DATE',
                        'UF_PASSPORT_ISSUED',
                        'UF_SNILS',
                        'UF_POSTCODE',
                        'UF_REGION',
                        'UF_AREA',
                        'UF_CITY',
                        'UF_STREET',
                        'UF_HOME',
                        'UF_HOUSING',
                        'UF_BUILDING',
                        'UF_APARTMENT',
                        'UF_CONTACT_PERSONAL',
                    ]
                ],
                'step-3' => [
                    'id' => 'step-3',
                    'label' => 'Шаг 3. Сведения об объекте',
                    'controls' => [
                        'UF_OBJECT_NAME',
                        'UF_OBJECT',
                        'UF_OBJECT_AREA',
                        'UF_OBJECT_CITY',
                        'UF_OBJECT_STREET',
                        'UF_OBJECT_HOME',
                        'UF_OBJECT_HOUSING',
                        'UF_OBJECT_BUILDING',
                        'UF_OBJECT_APARTMENT',
                        'UF_INVENTORY_NUMBER',
                        //'UF_MAX_POWER',
                        //'UF_LEVEL_VOLTAGE',
                        //'UF_CATEGORY_SECURITY',
                        'UF_CNT_POINT_CONNECT',
                        //'UF_VAL_TECH_ARMOR',
                        //'UF_VAL_CRASH_ARMOR',
                        //'UF_RATIONALE_ARMOR',
                    ]
                ],
                'step-4' => [
                    'id' => 'step-4',
                    'label' => 'Шаг 4. Направленные документы',
                    'controls' => [
                        'UF_FILE_PLAN', 'UF_FILES_EXT', 'UF_FILE_SCAN_PRAVO', 'UF_FILE_PROTOCOL', 'UF_FILE_DOVERENOST',
                    ]
                ]
            ],
            'ENTREPRENEUR' => [
                'step-1' => [
                    'id' => 'step-1',
                    'label' => 'Шаг 1. Общие сведения',
                    'controls' => ['UF_VIEW_REQUEST_SUB', 'UF_TYPE_GET_DOCUMENT', 'UF_LIST_OFFICE']
                ],
                'step-2' => [
                    'id' => 'step-2',
                    'label' => 'Шаг 2. Реквизиты заявителя',
                    'controls' => [
                        'UF_FIO',
                        'UF_BIRTHDAY',
                        'UF_PASSPORT_SERIES',
                        'UF_PASSPORT_CODE',
                        'UF_PASSPORT_DATE',
                        'UF_PASSPORT_ISSUED',
                        'UF_SNILS',
                        'UF_POSTCODE',
                        'UF_REGION',
                        'UF_AREA',
                        'UF_CITY',
                        'UF_STREET',
                        'UF_HOME',
                        'UF_HOUSING',
                        'UF_BUILDING',
                        'UF_APARTMENT',
                        'UF_CONTACT_PERSONAL',
                    ]
                ],
                'step-3' => [
                    'id' => 'step-3',
                    'label' => 'Шаг 3. Сведения об объекте',
                    'controls' => [
                        'UF_OBJECT_NAME',
                        'UF_OBJECT',
                        'UF_OBJECT_AREA',
                        'UF_OBJECT_CITY',
                        'UF_OBJECT_STREET',
                        'UF_OBJECT_HOME',
                        'UF_OBJECT_HOUSING',
                        'UF_OBJECT_BUILDING',
                        'UF_OBJECT_APARTMENT',
                        'UF_INVENTORY_NUMBER',
                        //'UF_MAX_POWER',
                        //'UF_LEVEL_VOLTAGE',
                        //'UF_CATEGORY_SECURITY',
                        'UF_CNT_POINT_CONNECT',
                        //'UF_VAL_TECH_ARMOR',
                        //'UF_VAL_CRASH_ARMOR',
                        //'UF_RATIONALE_ARMOR',
                    ]
                ],
                'step-4' => [
                    'id' => 'step-4',
                    'label' => 'Шаг 4. Направленные документы',
                    'controls' => [
                        'UF_FILE_PLAN', 'UF_FILES_EXT', 'UF_FILE_SCAN_PRAVO', 'UF_FILE_PROTOCOL', 'UF_FILE_DOVERENOST',
                    ]
                ]
            ],
            'ORGANIZATION' => [
                'step-1' => [
                    'id' => 'step-1',
                    'label' => 'Шаг 1. Общие сведения',
                    'controls' => ['UF_VIEW_REQUEST_SUB', 'UF_TYPE_GET_DOCUMENT', 'UF_LIST_OFFICE']
                ],
                'step-2' => [
                    'id' => 'step-2',
                    'label' => 'Шаг 2. Реквизиты заявителя',
                    'controls' => [
                        'UF_ORG_NAME',
                        'UF_ORG_UR_ADDRESS',
                        'UF_ORG_ADDRESS',
                        'UF_ORG_N_EGRUL',
                        'UF_ORG_DATE_EGRUL',
                        'UF_ORG_INN',
                        'UF_ORG_KPP',
                        'UF_ORG_BANK',
                        'UF_ORG_RS',
                        'UF_ORG_KS',
                        'UF_ORG_BIK',
                        'UF_ORG_OKPO',
                        'UF_ORG_OGRN',
                        'UF_PHONE',
                        'UF_ORG_OKVED',
                        'UF_CONTACT_PERSONAL',
                    ],
                ],
                'step-3' => [
                    'id' => 'step-3',
                    'label' => 'Шаг 3. Сведения об объекте',
                    'controls' => [
                        'UF_OBJECT_NAME',
                        'UF_OBJECT',
                        'UF_OBJECT_AREA',
                        'UF_OBJECT_CITY',
                        'UF_OBJECT_STREET',
                        'UF_OBJECT_HOME',
                        'UF_OBJECT_HOUSING',
                        'UF_OBJECT_BUILDING',
                        'UF_OBJECT_APARTMENT',
                        'UF_INVENTORY_NUMBER',
                        //'UF_MAX_POWER',
                        //'UF_LEVEL_VOLTAGE',
                        //'UF_CATEGORY_SECURITY',
                        'UF_CNT_POINT_CONNECT',
                        //'UF_VAL_TECH_ARMOR',
                        //'UF_VAL_CRASH_ARMOR',
                        //'UF_RATIONALE_ARMOR',
                    ]
                ],
                'step-4' => [
                    'id' => 'step-4',
                    'label' => 'Шаг 4. Направленные документы',
                    'controls' => [
                        'UF_FILE_PLAN', 'UF_FILES_EXT', 'UF_FILE_SCAN_PRAVO', 'UF_FILE_PROTOCOL', 'UF_FILE_DOVERENOST',
                    ]
                ]
            ]
        ];

        $this->arResult['HL_FIELDS'] = $controlsAll[$groupName];
        $this->arResult['STEPS'] = $stepsAll[$groupName];
    }

    /**
     * Возвращает данные из профиля и реквизитов организации
     * @param $orgID
     * @param $hlCodeRequisites
     * @return array
     */
    function getDataDefaultValues($orgID, $hlCodeRequisites)
    {
        $profile = (array)\CUser::GetList(
            $by1 = 'id',
            $order1 = 'asc',
            ['ID' => (new \CUser())->GetID()],
            ['SELECT' => ['UF_*']]
        )->Fetch();

        $orgID = intval($orgID);

        $requisites = [];
        if ($orgID > 0) {
            $hlRequisites = HL\Base::initByCode($hlCodeRequisites);
            $rsRequisites = $hlRequisites::getList([
                'filter' => ['ID' => $orgID]
            ]);
            if ($arRequisites = $rsRequisites->fetch()) {
                foreach ($arRequisites as $code => $value) {
                    $requisites[$code . '__REQUISITES'] = $value;
                }
            }
        }

        return array_merge($profile, $requisites);
    }

    /**
     * Замена по карте на реальные значения
     * @param $value
     * @param $dataDefaultValues
     * @return mixed
     */
    function replaceMapValue($value, $dataDefaultValues)
    {
        if (strpos($value, '#') !== false) {
            $anchorsFields = explode('#', $value);
            foreach ($anchorsFields as $key => $fieldCode) {
                if (strlen(trim($fieldCode)) > 0) {
                    if (array_key_exists($fieldCode, $dataDefaultValues)) {
                        $value = str_replace(
                            '#' . $fieldCode . '#',
                            $dataDefaultValues[$fieldCode],
                            $value
                        );
                    }
                }
            }
        }

        return $value;
    }

    /**
     * Возвращает данные заявления из черновика
     * @param $hlCode
     * @param $draftID
     * @return array|false
     */
    function getDataDraft($hlCode, $draftID)
    {
        global $USER;

        $resultDraft = [];
        $hl = HL\Base::initByCode($hlCode);
        $rsDraft = $hl::getList([
            'select' => ['ID', 'UF_DATA'],
            'filter' => [
                'UF_USER_ID' => $USER->GetID(),
                'ID' => $draftID
            ]
        ]);
        if ($arDraft = $rsDraft->fetch()) {
            $resultDraft = json_decode($arDraft['UF_DATA'], true);
        }

        return $resultDraft;
    }

    public function executeComponent()
    {
        $componentPage = $this->routers();

        // метод возвращает все массив групп в которых состоит пользователь
        $groupsCurrentUser = $this->getGroupsCurrentUser();

        // проверка на юезара и на модератора или админа
        if (!$this->checkAllowUser($groupsCurrentUser) && !$this->checkAllowModerator($groupsCurrentUser)) {
            ShowError('У вас нет прав.');
            return;
        }
        $this->includeComponentTemplate($componentPage);
    }
}