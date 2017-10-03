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
<? if ($arResult['FIELDS']):
    $arFields = $arResult['FIELDS'];
    ?>
    <h2>Заявление № <?= $arFields['ID'] ?> от <?= $arResult['DATE_INSERT'] ?></h2>
    <div class="detail">

            <? foreach ($arFields as $arField): ?>
                <div class="profile">
                    <div class="profile-left"><?= $arField['title'] ?></div>
                    <div class="profile-right">
                        <? if ($arField['type'] === 'file'): ?>
                            <? if ($arField['multiple'] === 'Y'): ?>
                                <? foreach ($arField['value'] as $arFile): ?>
                                    <a href="<?= $arFile['src'] ?>"><?= $arFile['name'] ?></a><br>
                                <? endforeach; ?>
                            <? else: ?>
                                <a href="<?= $arField['value']['src'] ?>"><?= $arField['value']['name'] ?></a>
                            <? endif; ?>
                        <? else: ?>
                            <?= $arField['value'] ?>
                        <? endif; ?>
                    </div>
                </div>
            <? endforeach; ?>

    </div>
<? else: ?>
    <p>Заявление № <?= intval($arParams['ELEMENT_ID']) ?> не найдено.</p>
<? endif; ?>
<p><a class="button" href="<?= $arParams['FOLDER'] ?>">Вернуться к списку</a> </p>

