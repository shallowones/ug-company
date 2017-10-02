<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 29.06.2017
 * Time: 12:35
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var StatementComponent $component
 * @var array $arResult
 * @var array $arParams
 */

global $APPLICATION;

if (\UW\Acl\Storage::get()->isAllowed('statementStatus', 'edit') === false) {
    ShowError('Доступ закрыт.');
    return;
}

$orgID = $component->getOrganizationID();

$APPLICATION->IncludeComponent(
    'ugraweb:hl.form',
    '',
    [
        'DETAIL_PAGE_URL' => $arParams['DETAIL_PAGE_URL'],
        'HL_CODE' => $arParams['HL_CODE'],
        'HL_CODE_REQUISITES' => $arParams['HL_CODE_REQUISITES'],
        'HL_FIELDS' => $arParams['HL_FIELDS_STATUS'],
        'STEPS' => $arParams['STEPS_STATUS'],
        'ORGANIZATION_ID' => $orgID,
        'HL_ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
    ],
    $component
);