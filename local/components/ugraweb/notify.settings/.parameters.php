<?
/** @var array $arCurrentValues */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
{
	die();
}

use Bitrix\Highloadblock as HL;

$arHBlocks = array();
$rsData = HL\HighloadBlockTable::getList();
while ($arData = $rsData->fetch())
{
    $arHBlocks[$arData['ID']] = $arData['NAME'];
}

$arUFields = array();
if (intval($arCurrentValues['HBLOCK_ID']) > 0)
{
    $ufEntityId = 'HLBLOCK_'.$arCurrentValues['HBLOCK_ID'];
    $rsUF = CUserTypeEntity::GetList(array($by => $order), array('ENTITY_ID' => $ufEntityId));
    while ($arUF = $rsUF->Fetch())
    {
        $arUserField = CUserTypeEntity::GetByID($arUF['ID']);
        $arUFields[$arUF['FIELD_NAME']] = '['.$arUF['FIELD_NAME'].'] '.$arUserField['EDIT_FORM_LABEL'][LANGUAGE_ID];
    }
}

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"CACHE_TIME"  => array("DEFAULT" => 36000000),
		"HBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => "Код Highload блока",
			"TYPE" => "LIST",
			"VALUES" => $arHBlocks,
			"DEFAULT" => '={$_REQUEST["ID"]}',
			"ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y"
		),
        "PROPERTY_CODES" => array(
            "PARENT" => "BASE",
            "NAME" => 'Свойства, выводимые в шаблоне',
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arUFields,
        ),
        "ITEMS_COUNT" => array(
            "PARENT" => "BASE",
            "NAME" => 'Количество элементов на странице',
            "TYPE" => "STRING",
            "DEFAULT" => "20",
        ),
        "DETAIL_PAGE_URL" => array(
            "PARENT" => "BASE",
            "NAME" => 'Шаблон детальной страницы',
            "TYPE" => "STRING",
            "DEFAULT" => "",
        )
	)
);

if (CModule::IncludeModule('iblock'))
{
    CIBlockParameters::AddPagerSettings($arComponentParameters, 'Элементы', true, true);
}
