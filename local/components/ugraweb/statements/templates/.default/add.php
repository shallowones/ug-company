<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 28.06.2017
 * Time: 17:00
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

use UW\Form\Controls;

/**
 * @var StatementComponent $component
 * @var array $arResult
 * @var array $arParams
 */

global $APPLICATION, $USER;

if (\UW\Acl\Storage::get()->isAllowed('statement', 'add') === false) {
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

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$arRequest = $request->toArray();

// Чтение данных из черновика
$arDraft = [];
if ($request->get('draft') !== null && count($arRequest['saveDraft']) < 1) {
    $arDraft = $APPLICATION->IncludeComponent(
        'ugraweb:hl.draft',
        '',
        [
            'HL_CODE' => $arParams['HL_CODE_DRAFTS'],
            'HL_CODE_STATEMENTS' => $arParams['HL_CODE'],
            'HL_FIELDS' => $arResult['HL_FIELDS'],
            'STEPS' => $arResult['STEPS'],
            'DRAFTS_PAGE_URL' => $arParams['DRAFTS_PAGE_URL'],
            'HL_DRAFT_ID' => intval($request->get('draft')),
        ],
        $component
    );
}

$steps = $APPLICATION->IncludeComponent(
    'ugraweb:hl.form',
    '',
    [
        'DETAIL_PAGE_URL' => $arParams['DETAIL_PAGE_URL'],
        'HL_CODE' => $arParams['HL_CODE'],
        'HL_CODE_REQUISITES' => $arParams['HL_CODE_REQUISITES'],
        'HL_CODE_DRAFTS' => $arParams['HL_CODE_DRAFTS'],
        'HL_FIELDS' => $arResult['HL_FIELDS'],
        'STEPS' => $arResult['STEPS'],
        'ORGANIZATION_ID' => $orgID,
        'HL_FIELDS_EXT' => [
            'UF_USER_ID' => $USER->GetID()
        ],
        'DRAFT_FIELDS' => $arDraft // принимаем в result_modifier шаблона (т.е. компонент не затрагиваем)
    ],
    $component
);

// Запоминаем текущий шаг
$currentStep = $steps->key();

// Делам редирект чтобы избавится от параметра draft
// Данные из черновика после чтения сохраняются в сессию (
// hl.form/.default/result_modifier.php - $controls->saveValues();)
// поэтому можно делать редирект после загрузки формы
if ($request->get('draft') !== null) {
    LocalRedirect($APPLICATION->GetCurPageParam('prevStep=' . $currentStep, ['prevStep', 'draft']));
}

// добавление или обновление черновика
if ($request->isPost() && count($arRequest['saveDraft']) > 0) {

    /* @var UW\Form\Steps $steps */
    // Необходимо пробежать по всем шагам, чтобы заменить карты на значения
    // Бежим по шагам
    $fieldsData = [];

    foreach ($arResult['STEPS'] as $arStep) {
        $steps->key($arStep['id']);
        /** @var UW\Form\Step $step */
        $step = $steps->current();
        /** @var \UW\Form\Input */
        $controls = $step->controls();

        // Для текущего шага принимаем данные из request (остальные шаги сохраняются из сессии, если были туда сохранены)
        if ($currentStep === $arStep['id']) {
            $controls->fill($arRequest);
        }

        foreach ($controls as $code => $control) {
            $fieldsData[$code] = $control->value;
        }
        $controls->clearValues();
    }

    $arDraft = $APPLICATION->IncludeComponent(
        'ugraweb:hl.draft',
        '',
        [
            'HL_CODE' => $arParams['HL_CODE_DRAFTS'],
            'DRAFTS_PAGE_URL' => $arParams['DRAFTS_PAGE_URL'],
            'HL_DRAFT_ID' => intval($request->get('dsave')),
            'FIELDS_DATA' => $fieldsData,
        ],
        $component
    );
}