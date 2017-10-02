<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 28.06.2017
 * Time: 17:01
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var StatementComponent $component
 * @var array $arResult
 * @var array $arParams
 */

global $APPLICATION, $USER;

if (\UW\Acl\Storage::get()->isAllowed('statement', 'edit') === false) {
    ShowError('Доступ закрыт.');
    return;
}

$orgID = $component->getOrganizationID();

$groups = $component->getGroupsCurrentUser($USER->getID());

$groupName = $component->checkAllowUser($groups);
if (!$groupName) {
    $groupName = $component->checkAllowModerator($groups);
}

$component->getGroupControls($groupName);

$APPLICATION->IncludeComponent(
    'ugraweb:hl.form',
    '',
    [
        'DETAIL_PAGE_URL' => $arParams['DETAIL_PAGE_URL'],
        'HL_CODE' => $arParams['HL_CODE'],
        'HL_CODE_REQUISITES' => $arParams['HL_CODE_REQUISITES'],
        'HL_FIELDS' => $arResult['HL_FIELDS'],
        'STEPS' => $arResult['STEPS'],
        'ORGANIZATION_ID' => $arResult['ORGANIZATION_ID'],
        'HL_ELEMENT_ID' => $arResult['VARIABLES']['ELEMENT_ID'],
    ],
    $component
);
