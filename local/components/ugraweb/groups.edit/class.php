<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CGroupsEdit extends CBitrixComponent
{
    /**
     * @param $arParams
     * @return mixed
     */
    public function onPrepareComponentParams($arParams)
    {
        // Убираем пустые элементы
        foreach ($arParams["GROUPS_FOR_SELECT_LIST"] as $key => $val) {
            if (!$val) {
                unset($arParams["GROUPS_FOR_SELECT_LIST"][$key]);
            }
        }

        // Если ничего не пришло, задаем группы поучмолчанию
        if (count($arParams["GROUPS_FOR_SELECT_LIST"]) < 1) {
            $arParams["GROUPS_FOR_SELECT_LIST"] = [
                0 => 'INDIVIDUALS',
                1 => 'ENTREPRENEUR',
                2 => 'ORGANIZATION'
            ];
        }

        // Преобразуем массив групп в строку для фильтра
        $arParams["GROUPS_FOR_SELECT_STRING"] = implode('|', $arParams["GROUPS_FOR_SELECT_LIST"]);

        // Проверяем параметр группы, в которую переходит пользователь после регистрации
        if (empty($arParams['GROUP_AFTER_REGISTRATION'])) {
            $arParams['GROUP_AFTER_REGISTRATION'] = 'REGISTERED_USERS';
        }

        return $arParams;
    }

    /**
     * Проверяет на необходимость выбора группы
     * @return $this
     */
    private function checkGroup()
    {
        global $USER;
        $groupsCurrentUser = [];

        $params = [
            'select' => [
                'STRING_ID' => 'GROUP.STRING_ID',
                'GROUP_ID'
            ],
            'filter' => [
                'USER_ID' => $USER->GetID()
            ]
        ];
        $rsGetUserGroupList = \Bitrix\Main\UserGroupTable::getList($params);
        while ($arGetUserGroupList = $rsGetUserGroupList->fetch()) {
            $groupsCurrentUser[$arGetUserGroupList['STRING_ID']] = $arGetUserGroupList['GROUP_ID'];
        }

        $rsGroup = CGroup::GetList(
            $by1 = 'ID',
            $order1 = 'ASC',
            ['STRING_ID' => $this->arParams['GROUP_AFTER_REGISTRATION']]
        );
        if ($arGroup = $rsGroup->Fetch()) {
            // если группа "Зарегистрированные пользователи" найдена в группах текущего пользователя,
            // то устанавливаем признак, что необходимо выбрать группу
            if (in_array($arGroup['ID'], $groupsCurrentUser)) {
                $this->arResult['SELECTED_GROUP'] = false;
            } else {
                $this->arResult['SELECTED_GROUP'] = true;
            }
        }

        return $this;
    }

    /**
     * Возвращает список групп для выбора
     * @return $this
     */
    private function getGroups()
    {
        $rsGroupsSelect = CGroup::GetList(
            $by1 = 'ID', $order1 = 'ASC',
            ['STRING_ID' => $this->arParams["GROUPS_FOR_SELECT_STRING"]]
        );
        while ($arGroupSelect = $rsGroupsSelect->Fetch()) {
            $this->arResult['GROUPS_SELECT'][] = [
                'ID' => $arGroupSelect['ID'],
                'NAME' => $arGroupSelect['NAME']
            ];
        }

        return $this;
    }

    /**
     * Сохраняет группу
     * @return $this
     */
    private function saveGroup()
    {
        // работа с установкой новой группы пользователя
        $obRequest = $this->request;
        $arRequest = $obRequest->toArray();
        if ($arRequest['select-submit'] === 'Y' && $arRequest['group-select']) {
            $obUser = new CUser();
            $obUser->SetUserGroup($obUser->GetID(), $arRequest['group-select']);
            LocalRedirect($obRequest->getRequestUri());
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function executeComponent()
    {
        $this
            ->getGroups()
            ->saveGroup()
            ->checkGroup()
            ->IncludeComponentTemplate();

        return $this->arResult['SELECTED_GROUP'];
    }
}

