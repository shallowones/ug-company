<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
?>
    <h1 class="h1">Профиль</h1>

    <? ShowError($arResult['strProfileError']); ?>
    <?
    if ($arResult['DATA_SAVED'] == 'Y')
        ShowNote(GetMessage('PROFILE_DATA_SAVED'));
    ?>


    <form class="profile__form" method="post" name="profile-edit" action="<? echo $arResult['FORM_TARGET'] ?>" enctype="multipart/form-data">
        <? echo $arResult['BX_SESSION_CHECK'] ?>
        <input type="hidden" name="LOGIN" value="<? echo $arResult['arUser']['LOGIN'] ?>"/>
        <input type="hidden" name="EMAIL" value="<? echo $arResult['arUser']['EMAIL'] ?>"/>

            <? foreach ($arResult['USER'] as $row): ?>
                <div class="profile">
                    <div class="profile-left">
                        <? echo $row['NAME'] . ':' ?>
                    </div>
                    <div class="profile-right">
                        <input class="profile__text" type="text" name="<? echo $row['CODE'] ?>" value="<? echo $row['VALUE'] ?>" title="<? echo $row['NAME'] ?>"/>
                    </div>
                </div>
            <? endforeach; ?>

        <div class="buttons">
            <button class="button go" type="submit" name="save" value="Y"><? echo GetMessage('MAIN_SAVE') ?></button>
        </div>
    </form>

<script>

</script>