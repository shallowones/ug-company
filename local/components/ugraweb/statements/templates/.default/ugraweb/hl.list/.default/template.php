<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
?>

    <div class="content-left">
        <div class="table tab-block" id="all">
            <div class="table-line head">
                <div class="table-line__number">Номер</div>
                <div class="table-line__date">Дата</div>
                <div class="table-line__name">Наименование</div>
                <div class="table-line__state">Текущий статус</div>
            </div>
            <? if (count($arResult['LIST']) > 0): ?>
                <? foreach ($arResult['LIST'] as $key => $arStatement): ?>
                <a href="<?= $arStatement['DETAIL_URL'] ?>">
                        <div class="table-line">
                            <div class="table-line__number">#<?= $arStatement['ID']?></div>
                            <div class="table-line__date"><?= $arStatement['DATE_INSERT']?></div>
                            <div class="table-line__name"><?= $arStatement['UF_VIEW_REQUEST_SUB']?></div>
                            <div class="table-line__state"><?= $arStatement['UF_STATUS']?></div>
                        </div>
                </a>
                <? endforeach; ?>
                <? if ($arResult['NAV_STRING']): ?>
                    <br><?= $arResult['NAV_STRING'] ?>
                <? endif; ?>
            <? else: ?>
                <p>Пока нет ни одного заявления</p>
            <? endif; ?>

        </div>
        <div class="table tab-block" id="complited">
            <div class="table-line head">
                <div class="table-line__number">Номер2</div>
                <div class="table-line__date">Дата2</div>
                <div class="table-line__name">Наименование2</div>
                <div class="table-line__state">Текущий статус2</div>
            </div>
        </div>
    </div>
