<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arParams
 * @var array $arResult
 */

?>
<div class="profile-left">
    <div id="<? echo $arParams['UI']['CONTAINER'] ?>">
        <button class="button__state" id="<? echo $arParams['PLUPLOAD_OPTIONS']['browse_button'] ?>">Выбрать файл</button>
        <button class="button__state" id="<? echo $arParams['UI']['START_UPLOAD'] ?>">Начать загрузку</button>

    </div>
</div>

<div class="profile-right">
    <pre style="color: red" id="<? echo $arParams['UI']['CONSOLE'] ?>"></pre>
<? if ($arResult['restoreValues'] && $arParams['MULTIPLE'] === 'Y'): ?>
    <ul id="restore_files_list">
        <? foreach ($arResult['restoreValues'] as $arFile): ?>
            <li id="<?= randString(20)?>">
                <p><?= $arFile['name']?></p>
                <input name="<?= $arParams['INPUT_NAME']?>[]" value="<?= $arFile['id']?>" type="hidden">
            </li>
        <? endforeach; ?>
    </ul>
<? endif; ?>
<ul id="<? echo $arParams['UI']['FILE_LIST'] ?>">
    <? if ($arResult['restoreValues'] && $arParams['MULTIPLE'] === 'N'): ?>
        <? foreach ($arResult['restoreValues'] as $arFile): ?>
            <li id="<?= randString(20)?>">
                <p><?= $arFile['name']?></p><br>
                <input name="<?= $arParams['INPUT_NAME']?>" value="<?= $arFile['id']?>" type="hidden">
            </li>
        <? endforeach; ?>
    <? endif; ?>
</ul>
</div>


