<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */

// работа с шаблоном редактирования профиля
$arProps = $arResult['USER_PROPERTIES']['DATA'];
$arUser = $arResult['arUser'];
$user = [];

foreach ($arParams['USER_ROWS'] as $rowName) { // rows
    $user[] = [
        'NAME' => GetMessage($rowName),
        'CODE' => $rowName,
        'VALUE' => $arUser[$rowName]
    ];
}
foreach ($arParams['USER_PROPERTY'] as $rowName) { // rows
    $user[] = [
        'NAME' => $arProps[$rowName]['EDIT_FORM_LABEL'],
        'CODE' => $rowName,
        'VALUE' => $arUser[$rowName]
    ];
}
$arResult['USER'] = $user;