<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arParams
 */

if (intval($arParams['VALUE']) > 0) {
    $arResult['restoreValues'] = [];
    $arFile = CFile::GetFileArray($arParams['VALUE']);
    $arResult['restoreValues'][] = [
        'id' => $arFile['ID'],
        'name' => $arFile['ORIGINAL_NAME'],
    ];
}

if (is_array($arParams['VALUE']) && count($arParams['VALUE']) > 0) {
    $arResult['restoreValues'] = [];
    foreach ($arParams['VALUE'] as $fileID) {
        $arFile = CFile::GetFileArray($fileID);
        $arResult['restoreValues'][] = [
            'id' => $arFile['ID'],
            'name' => $arFile['ORIGINAL_NAME'],
        ];
    }
}