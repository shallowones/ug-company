<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 14.07.2017
 * Time: 18:32
 * @var array $arParams
 * @var array $arResult
 * @var StatementComponent $statements
 * @var UW\Form\Steps $steps
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

// Загрузка данных поумолчанию из профиля и реквизитов организации)
$statements = $this->__component->__parent;
$dataDefaultValues = $statements->getDataDefaultValues($arParams['ORGANIZATION_ID'], $arParams['HL_CODE_REQUISITES']);

// Необходимо пробежать по всем шагам, чтобы заменить карты на значения
$steps = $arResult['steps'];
// Запоминаем текущий шаг
$currentStep = $steps->key();
// Бежим по шагам
foreach ($arParams['STEPS'] as $arStep) {
    $steps->key($arStep['id']);
    /** @var UW\Form\Step $step */
    $step = $steps->current();
    /** @var \UW\Form\Input */
    $controls = $step->controls();

    // заменяем карты на значения из профиля и реквизитов
    foreach ($controls as $control) {
        $control->value = $statements->replaceMapValue($control->value, $dataDefaultValues);
    }

    // Загрузка данных из черновика
    if (!empty($arParams['DRAFT_FIELDS']) && count($arParams['DRAFT_FIELDS']) > 0) {
        foreach ($controls as $code => $control) {
            if (array_key_exists($code, $arParams['DRAFT_FIELDS'])) {
                $control->value = $arParams['DRAFT_FIELDS'][$code];
            }
        }
    }
    // Сохраняем загруженные данные в хранилище для всех шагов
    // чтобы потом можно было сделать редирект
    $controls->saveValues();
}

// Воссстанволение шага
$steps->key($currentStep);
$arResult['step'] = $steps->current();
$arResult['nextStep'] = $steps->nextStepCode();
$arResult['prevStep'] = $steps->prevStepCode();
