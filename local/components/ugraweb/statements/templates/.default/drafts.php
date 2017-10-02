<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 05.07.2017
 * Time: 19:54
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arResult
 * @var array $arParams
 * @var HighloadDraftComponent $component
 */

global $APPLICATION;

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();

$APPLICATION->IncludeComponent(
    'ugraweb:hl.list',
    'drafts',
    [
        'DETAIL_URL' => $arParams['DRAFTS_ADD_URL'],
        "HL_CODE" => $arParams["HL_CODE_DRAFTS"],
        "DRAFTS_PAGE_URL" => $arParams['DRAFTS_PAGE_URL'],
        "DELETE_ELEMENT_ID" => $request->get('draftDelete')
    ],
    $component
);
