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
    <table class="temp-table">
        <? foreach ($arFields as $arField): ?>
            <tr>
                <td><b><?= $arField['title'] ?></b></td>
                <td>
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
                </td>
            </tr>
        <? endforeach; ?>
    </table>
<? else: ?>
    <p>Заявление № <?= intval($arParams['ELEMENT_ID']) ?> не найдено.</p>
<? endif; ?>
<p><a href="<?= $arParams['FOLDER'] ?>">Вернуться к списку</a></p>
