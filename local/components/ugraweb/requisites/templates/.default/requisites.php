<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var StatementComponent $component
 * @var array $arResult
 * @var array $arParams
 */

global $APPLICATION, $USER;

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
        'HL_CODE' => $arParams['HL_CODE'],
        'HL_ELEMENT_ID' => $orgID,
        'HL_FIELDS' => $arResult['HL_FIELDS'],
        'STEPS' => $arResult['STEPS']
    ],
    $component
);