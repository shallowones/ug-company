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
    <h2>Список элементов</h2>
<? if (count($arResult['LIST']) > 0): ?>
    <? foreach ($arResult['LIST'] as $key => $arElement): ?>
        <p>
            <a href="<?= $arElement['DETAIL_URL'] ?>">Элемент <?= $arElement['ID'] ?></a>
        </p>
    <? endforeach; ?>
    <? if ($arResult['NAV_STRING']): ?>
        <br><?= $arResult['NAV_STRING'] ?>
    <? endif; ?>
<? else: ?>
    <p>Пока нет ни одного элемента</p>
<? endif; ?>