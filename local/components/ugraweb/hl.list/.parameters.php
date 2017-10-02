<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$arComponentParameters = array(
    "PARAMETERS" => array(),
);

// Пример параметров, задаются из кода

//[
//    'DETAIL_URL' => "/_dev/#ELEMENT_ID#/",
//    "HL_CODE" => 'Statements',
//    "ORDER" => ['UF_DATE_INSERT' => 'ASC'],
//    "FIELDS" => [
//        'ID',
//        'UF_DATE_INSERT'
//    ],
//    "FILTER" => $extFilter,
//    "ITEMS_COUNT" => 2,
//    "RUNTIME" => [
//        new \Bitrix\Main\Entity\ExpressionField(
//            'STATUS',
//            'FIELD(%s, "' . UW\Modules\Cades\Highload\UserStatusEnum::CREATED . '")',
//            ['UF_STATUS']
//        ),
//    ],
//],