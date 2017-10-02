<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @global CMain $APPLICATION
 * @var FileUploader $this
 */

if ($this->arParams['DEV_MODE'] !== true) {
    ob_start();
}

$APPLICATION->IncludeFile($this->getTemplate()->GetFolder() . '/script.php', [
    'OPTIONS' => $this->arParams['PLUPLOAD_OPTIONS'],
    'UI' => $this->arParams['UI']
]);

if ($this->arParams['DEV_MODE'] !== true) {
    $script = ob_get_contents();
    ob_end_clean();

    $this->asset->addString($script, true, \Bitrix\Main\Page\AssetLocation::AFTER_JS);
}
