<?php
/**
 * Created by PhpStorm.
 * User: Владимир
 * Date: 03.07.2017
 * Time: 18:57
 */

/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

foreach ($arResult['LIST'] as &$arHlElement) {
    // Дата элемента
    /** @var Bitrix\Main\Type\DateTime $date */
    $date = $arHlElement['UF_DATE_INSERT'];
    $arHlElement['DATE_INSERT'] = $date->format('d.m.Y H:i');
}
