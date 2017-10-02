<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$rsGroup = CGroup::GetList($by1 = 'ID', $order1 = 'ASC');
while ($arGroup = $rsGroup->Fetch()) {
    $arGroups[$arGroup['STRING_ID']] = $arGroup["NAME"];
}

$arComponentParameters = array(
	"PARAMETERS" => array(
        "GROUPS_FOR_SELECT_LIST" => array(
            "PARENT" => "BASE",
            "NAME" => 'Группы для выбора',
            "TYPE" => "LIST",
            "VALUES" => $arGroups,
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE" => "Y",
        ),
        "GROUP_AFTER_REGISTRATION" => array(
            "PARENT" => "BASE",
            "NAME" => "Группа, в которую переходит пользователь после регистрации",
            "TYPE" => "LIST",
            "VALUES" => $arGroups,
            "ADDITIONAL_VALUES" => "Y",
        ),
	),
);
?>