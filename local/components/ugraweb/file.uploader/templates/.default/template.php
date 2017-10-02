<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arParams
 * @var array $arResult
 */

?>
<? if ($arResult['restoreValues'] && $arParams['MULTIPLE'] === 'Y'): ?>
    <ul id="restore_files_list">
        <? foreach ($arResult['restoreValues'] as $arFile): ?>
            <li id="<?= randString(20)?>">
                <?= $arFile['name']?>
                <input name="<?= $arParams['INPUT_NAME']?>[]" value="<?= $arFile['id']?>" type="hidden">
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>
<ul id="<? echo $arParams['UI']['FILE_LIST'] ?>">
    <? if ($arResult['restoreValues'] && $arParams['MULTIPLE'] === 'N'): ?>
        <? foreach ($arResult['restoreValues'] as $arFile): ?>
            <li id="<?= randString(20)?>">
                <?= $arFile['name']?>
                <input name="<?= $arParams['INPUT_NAME']?>" value="<?= $arFile['id']?>" type="hidden">
            </li>
        <? endforeach; ?>
    <? endif; ?>
</ul>
<br>
<div id="<? echo $arParams['UI']['CONTAINER'] ?>">
    <button id="<? echo $arParams['PLUPLOAD_OPTIONS']['browse_button'] ?>">Выбрать файл</button>
    <button id="<? echo $arParams['UI']['START_UPLOAD'] ?>">Начать загрузку</button>
</div>
<br>
<pre id="<? echo $arParams['UI']['CONSOLE'] ?>"></pre>
