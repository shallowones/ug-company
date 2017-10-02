<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * @var array $arResult
 * @var array $arParams
 */
?>

<h2>Список черновиков</h2>
<? if ($arResult['drafts']): ?>
    <? foreach ($arResult['drafts'] as $arDraft): ?>
        <p>
            <a href="<?= $arParams['ADD_PAGE_URL'] ?>?draft=<?= $arDraft['ID'] ?>">
                <?= $arDraft['UF_NAME'] ?>
            </a>&nbsp;
            <a
                    href="?draft=<?= $arDraft['ID'] ?>&delete=Y"
                    class="draft-delete-link"
                    onclick="return confirm('Удалить черновик?');"
            >
                x
            </a>
        </p>
    <? endforeach; ?>
<? else: ?>
    <p>Пока нет черновиков.</p>
<? endif; ?>
