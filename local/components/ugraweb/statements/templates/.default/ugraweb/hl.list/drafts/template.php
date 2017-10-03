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
    <h2>Список черновиков</h2>
<? if (count($arResult['LIST']) > 0): ?>
    <? foreach ($arResult['LIST'] as $key => $arStatement): ?>
        <div class="profile">
            <div class="profile-left">
                <a href="<?= $arStatement['DETAIL_URL'] ?>"><p>Черновик № <?= $arStatement['ID'] ?>
                    от <?= $arStatement['DATE_INSERT'] ?></p></a>
            </div>
            <div class="profile-right">
                <a
                        href="?draftDelete=<?= $arStatement['ID'] ?>"
                        class="draft-delete-link"
                        onclick="return confirm('Удалить черновик?');"
                >
                   <p>Удалить</p>
                </a>
            </div>
        </div>
    <? endforeach; ?>
    <? if ($arResult['NAV_STRING']): ?>
        <br><?= $arResult['NAV_STRING'] ?>
    <? endif; ?>
<? else: ?>
    <p>Пока нет ни одного черновика</p>
<? endif; ?>