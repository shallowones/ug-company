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
    <h2 class="h2">Сообщения:</h2>
    <? foreach ($arResult['LIST'] as $arItem) { ?>
        <div class="comment-block">
            <div>
                <? echo $arItem['DATE_INSERT']; ?>
            </div>
            <div id="block_msg_<?= $arItem['ID'] ?>">
                <p><?= $arItem['UF_MESSAGE'] ?></p>
            </div>
        </div>
    <? } ?>
    <?= $arResult["NAV_STRING"] ?>
<? } ?>