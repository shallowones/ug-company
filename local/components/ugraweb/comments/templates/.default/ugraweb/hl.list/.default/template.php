<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */
?>
<? if (count($arResult['LIST']) > 0) { ?>
    <a name="messages"></a>
    <div class="request_text">
        <span><h3>Сообщения:</h3></span>
    </div>
    <? foreach ($arResult['LIST'] as $arItem) { ?>
        <div class="request_who_files">
                <span class="time_text">
                    <? echo $arItem['DATE_INSERT']; ?>
                </span>
            <div class="request_who_files_text" id="block_msg_<?= $arItem['ID'] ?>">
                <p><?= $arItem['UF_MESSAGE'] ?></p>
            </div>
        </div>
    <? } ?>
    <?= $arResult["NAV_STRING"] ?>
<? } ?>