<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @var CComments $component
 * @var array $arResult
 * @var array $arParams
 */

global $USER;

$extFilter = [
    'UF_STATEMENT_ID' => $arParams['ID']
];

$APPLICATION->IncludeComponent(
    'ugraweb:hl.list',
    '',
    [
        "HL_CODE" => $arParams["HL_CODE"],
        "FILTER" => $extFilter,
        "ORDER" => ['UF_DATE_INSERT' => 'DESC'],
        "ITEMS_COUNT" => 20,
    ],
    $component
);

$APPLICATION->IncludeComponent(
    'ugraweb:hl.form',
    '',
    [
        'HL_CODE' => $arParams['HL_CODE'],
        'HL_FIELDS' => [
            'UF_MESSAGE'
        ],
        'STEPS' => [
            'step-1' => [
                'id' => 'step-1',
                'label' => 'Ваше сообщение',
                'controls' => ['UF_MESSAGE']
            ]
        ],
        'HL_FIELDS_EXT' => [
            'UF_USER_ID' => $USER->GetID(),
            'UF_STATEMENT_ID' => $arParams['ID']
        ]
    ],
    $component
);

$obRequest = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$arRequest = $obRequest->toArray();

if (strlen($arRequest['message']) > 0 && $obRequest->isPost()) {
    LocalRedirect($obRequest->getRequestedPageDirectory() . '/#messages');
}
