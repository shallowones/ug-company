<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
\Bitrix\Main\Loader::includeModule('highloadblock');
use Bitrix\Highloadblock as HL;

$arHBlocks = array();
$rsData = HL\HighloadBlockTable::getList();
while ($arData = $rsData->fetch())
{
    $arHBlocks[$arData['NAME']] = $arData['NAME'];
}

$rsGroup = CGroup::GetList($by1 = 'ID', $order1 = 'ASC');
while ($arGroup = $rsGroup->Fetch()) {
    $arGroups[$arGroup['STRING_ID']] = $arGroup["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(
		
	),
	"PARAMETERS" => array(
		"VARIABLE_ALIASES" => Array(
            "requisites" => Array("NAME" => 'Форма реквизитов'),
		),		
		"SEF_MODE" => Array(
			"requisites" => array(
				"NAME" => 'Форма реквизитов',
				"DEFAULT" => "",
				"VARIABLES" => array(),
			),
		),
				
		"AJAX_MODE" => array(),
		
		"CACHE_TIME"  =>  Array("DEFAULT"=>36000000),
		"CACHE_FILTER" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("BN_P_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BN_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
        "GROUPS_FOR_SELECT_LIST" => array(
            "PARENT" => "BASE",
            "NAME" => 'Группы, для которых разрешен доступ',
            "TYPE" => "LIST",
            "VALUES" => $arGroups,
            "ADDITIONAL_VALUES" => "Y",
            "MULTIPLE" => "Y",
            "SIZE" => "10"
        ),
        "GROUP_MODERATORS" => array(
            "PARENT" => "BASE",
            "NAME" => 'Группа модераторов',
            "TYPE" => "LIST",
            "VALUES" => $arGroups,
            "ADDITIONAL_VALUES" => "Y",
            "SIZE" => "10"
        ),
        "HL_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => "Код HL-блока",
            "TYPE" => "LIST",
            "VALUES" => $arHBlocks,
            "DEFAULT" => '={$_REQUEST["ID"]}',
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y"
        ),
        "ORGANIZATION_FIELD_CODE" => array(
            "PARENT" => "BASE",
            "NAME" => 'Код поля ID организации',
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ),
	),
);



?>
