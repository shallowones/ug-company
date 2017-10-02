<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
?>

<h2>Редактирование профиля</h2>

<? ShowError($arResult['strProfileError']); ?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
    ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>

<form method="post" name="profile-edit" action="<? echo $arResult['FORM_TARGET'] ?>" enctype="multipart/form-data">
    <? echo $arResult['BX_SESSION_CHECK'] ?>
    <input type="hidden" name="LOGIN" value="<? echo $arResult['arUser']['LOGIN'] ?>"/>
    <input type="hidden" name="EMAIL" value="<? echo $arResult['arUser']['EMAIL'] ?>"/>

    <table>
        <tbody>
        <? foreach ($arResult['USER'] as $row): ?>
            <tr>
                <td><? echo $row['NAME'] . ':' ?></td>
                <td><input type="text" name="<? echo $row['CODE'] ?>" value="<? echo $row['VALUE'] ?>" title="<? echo $row['NAME'] ?>"/></td>
            </tr>
        <? endforeach; ?>
        </tbody>
    </table>

    <button type="submit" name="save" value="Y"><? echo GetMessage('MAIN_SAVE') ?></button>
</form>


