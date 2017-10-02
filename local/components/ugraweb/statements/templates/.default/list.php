<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var StatementComponent $component
 * @var array $arResult
 * @var array $arParams
 */

global $APPLICATION;

$extFilter = [];
// Если не модератор, то фильтруем список заявления по текущему пользователю
if ($arResult['IS_MODERATOR'] !== 'Y') {
    global $USER;
    $extFilter = ['UF_USER_ID' => $USER->GetID()];
}

$APPLICATION->IncludeComponent(
    'ugraweb:hl.list',
    '',
    [
        'DETAIL_URL' => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["detail"],
        "HL_CODE" => $arParams["HL_CODE"],
        "FILTER" => $extFilter
    ],
    $component
);
