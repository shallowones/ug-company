<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @global CUser $USER */
/** @global CUserTypeManager $USER_FIELD_MANAGER */

global $USER, $USER_FIELD_MANAGER;

$arRows = [];

// получим стандартный набор полей пользователя
$skip = [
    'ID',
    'PASSWORD',
    'ACTIVE',
    'DATE_REGISTER',
    'LAST_LOGIN',
    'LAST_ACTIVITY_DATE',
    'EXTERNAL_AUTH_ID',
    'XML_ID',
    'BX_USER_ID',
    'CONFIRM_CODE',
    'LID',
    'TIME_ZONE_OFFSET',
    'PERSONAL_MOBILE'
];

$rsUser = (new \Bitrix\Main\UserTable())->GetByID($USER->GetID());
$arUser = $rsUser->Fetch();
foreach ($arUser as $key => $value) {
    if (!in_array($key, $skip) && $name = GetMessage($key)) {
        $arRows[$key] = $name;
    }
}

$arTemplateParameters = array(
    "USER_ROWS" => array(
        "PARENT" => "ADDITIONAL_SETTINGS",
        "NAME" => 'Поля, редактируемые пользователем',
        "TYPE" => "LIST",
        "VALUES" => $arRows,
        "MULTIPLE" => "Y",
        "DEFAULT" => array(),
        "SIZE" => "10"
    ),
);